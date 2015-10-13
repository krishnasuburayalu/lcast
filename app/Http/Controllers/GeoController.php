<?php namespace LCast\Http\Controllers;

use LCast\Http\Requests;
use LCast\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Queue\Capsule\Manager as Queue;
use LCast\BCUser;
use LCast\Http\Requests\TaskCreateFormRequest;
use LCast\Commands\QueueGeocode;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pheanstalk\Pheanstalk;
use Elasticsearch\Client;
use LCast\GeoHelper;

class GeoController extends Controller {
    /**
     * Make sure the user is authenticated.
     * 
     */
    public function __construct()
    {

    }
     /**
     * landing controller routine
     * 
     */
    public function index()
    {
         $user = BCUser::find(5);
         dd($user);
    }

     /**
     * Indexing by profile id - mysql id
     * 
     */

    public function import()
    {
        $csvfile =  storage_path() .  '/source_dir/njcityzip.csv';
        $csv = \Excel::load($csvfile, 'UTF-8')->toArray();
        $is_empty = TRUE;
        foreach($csv as $data){
            if(array_get($data, 'postal', FALSE) !== FALSE ){
                $data['processed_date'] = GeoHelper::get_today_date();
                switch(array_get($data, 'command', 'A')){
                    case 'A':
                        $queue_message['action'] = GeoHelper::QUEUE_METHOD_PUT;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $response['profile'][] = array('id' => $data['postal'], 'status' => 'success', 'action' => 'Add');
                    break;
                   case 'D':
                        $queue_message['action'] = GeoHelper::QUEUE_METHOD_DELETE;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $response['profile'][] = array('id' => $data['postal'], 'status' => 'success', 'action' => 'Add');
                    break;
                    default:
                        $queue_message['action'] = GeoHelper::QUEUE_METHOD_PUT;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $response['profile'][] = array('id' => $data['postal'], 'status' => 'success', 'action' => 'Add');
                    break;
                }
                $is_empty = FALSE;
            }
        }
        if( $is_empty === TRUE ){
            return \Response::json(array(
                'error' => true,
                'response' => array('error' => true, 'message' => 'profile not found.')),
                404
            );
        }
       return \Response::json(array(
            'error' => false,
            'response' => $response),
            200
        );
    }

     

     public function rebuild_index()
    {
        GeoHelper::delete_index();
        GeoHelper::add_index();exit;

    }

    public function show($id)
    {
        $params = GeoHelper::get_elastic_config();
        $params['id']  = $id;
        $is_exists =\Es::exists($params);
        if(!$is_exists){
             return \Response::json(array(
            'error' => true,
            'response' => $is_exists),
            404
            );
        }
        $params['_source']    = TRUE;
        $results =\Es::get($params);
        return \Response::json(array(
            'error' => false,
            'response' => array_get($results, '_source')),
            200
        );
    }

    /**
     * Search profiles by Query in the Lucene query string syntax
     * 
     */

    public function search()
    {
        $q = \Input::get('q' , '');
        $size = (int) \Input::get('size' , 10);
        $skip = (int) \Input::get('skip' , 0);
        $fields = \Input::get('fields' , 'city,postal,state');
        $params = GeoHelper::get_elastic_config();
        if($fields == ''){
           $params['_source'] = TRUE;
        }else{
           $params['_source_include'] =$fields;
        }
        $params['size']    = $size;
        $params['from']    = $skip;
        $params['body']['query']['query_string']['query']  = $q;
        $results =\Es::search($params);
        $count = array_get($results, 'hits.total', 0);
        if($count <= 0 ){
            return \Response::json(array(
                'error' => true,
                'response' => array('error' => true, 'message' => 'profile not found.')),
                404
            );
        }
        return \Response::json(array(
            'error' => false,
            'response' => $results['hits']),
            200
        );
    }

    /**
     * delete profile info from elasticsearch by profile id
     * 
     */

    public function delete($id)
    {
        $queue_message['action'] = GeoHelper::QUEUE_METHOD_DELETE;
        $queue_message['data'] = array('id' => $id);
        $this->push_into_queue($queue_message);
        return \Response::json( GeoHelper::get_success_response(),
            200
        );
    }


    public function facets()
    {
        $q = \Input::get('q' , '');
        $size = (int) \Input::get('size' , 10);
        $skip = (int) \Input::get('skip' , 0);
        $field = \Input::get('field' , 'degree');
        $params = array();
        $params = GeoHelper::get_elastic_config();
        $params['size']    = $size;
        $params['from']    = $skip;

        $facet = '{"query":{"query_string":{"query":"'.$q.'"}},"facets":{"tags":{"terms":{"field":"'.$field.'"}}}}';
        $params['body']=$facet;
        $results =\Es::search($params);
        $count = array_get($results, 'hits.total', 0);
       if($count <= 0 ){
            return \Response::json(array(
                'error' => true,
                'response' => array('error' => true, 'message' => 'profile not found.')),
                404
            );
        }
       return \Response::json(array(
            'error' => false,
            'response' => $results['facets']),
            200
        ); 

    }


    public function push_into_queue($geo_params)
    {
        $this->dispatch(new QueueGeocode($geo_params));

    }
}
