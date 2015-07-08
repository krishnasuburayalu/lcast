<?php namespace LCast\Http\Controllers;

use LCast\Http\Requests;
use LCast\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Queue\Capsule\Manager as Queue;
use LCast\BCUser;
use LCast\Http\Requests\TaskCreateFormRequest;
use LCast\Commands\QueueProfile;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pheanstalk\Pheanstalk;
use Elasticsearch\Client;
use LCast\ProfileHelper;

class ProfileController extends Controller {
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
     * Bulk indexing, start and end needs to be passed
     * 
     */
     public function add_bulk()
    {
        $response = array();
        $start = \Input::get('start' , 0);
        $end = \Input::get('end' , 1);
        $size =    $end - $start;
        $users = BCUser::take($size)->skip($start)->get();
        $response['count'] = $users->count();
        foreach($users as $user){
              $user->albumes =  $user->albums()->get(['type','title','source','description','status'])->toArray();
            $user->appearances =  $user->appearances()->get(['user_age','height_cm','height_feet','weight_kg','weight_pound','built','hair_style','hair_color','skin_color','eye_color'])->toArray();
            $user->experiences =  $user->experiences()->get(['type','role','title','from_date','to_date','description'])->toArray();
            $user->profile =  $user->profile()->get(['name','gender','dob','language','state','country','capability','avatar','about_me'])->toArray();
            $user->qualifications =  $user->qualifications()->get(['type','title','from_date','to_date','description'])->toArray();
            //Push into queue
            $queue_message['action'] = ProfileHelper::QUEUE_METHOD_PUT;
            $queue_message['data'] = $user->toArray();
            $this->push_into_queue($queue_message);
            $response['profile'][] = array('id' => $user->id, 'status' => 'success'); 
        }
         if($response['count']  <= 0 ){
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
    /**
     * Indexing by profile id - mysql id
     * 
     */

    public function add($id)
    {
        $response = array();
        $user = BCUser::find($id);
        $user->albumes =  $user->albums()->get(['type','title','source','description','status'])->toArray();
        $user->appearances =  $user->appearances()->get(['user_age','height_cm','height_feet','weight_kg','weight_pound','built','hair_style','hair_color','skin_color','eye_color'])->toArray();
        $user->experiences =  $user->experiences()->get(['type','role','title','from_date','to_date','description'])->toArray();
        $user->profile =  $user->profile()->get(['name','gender','dob','language','state','country','capability','avatar','about_me'])->toArray();
        $user->qualifications =  $user->qualifications()->get(['type','title','from_date','to_date','description'])->toArray();
        //Push into queue
        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_PUT;
        $queue_message['data'] = $user->toArray();
        $this->push_into_queue($queue_message);
        $response['profile'][] = array('id' => $user->id, 'status' => 'success'); 
        if($user->count()  <= 0 ){
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
    /**
     * Get profile info from elasticsearch by profile id
     * 
     */

    public function update($id)
    {
        $params = ProfileHelper::get_elastic_config();
        $params['id']  = $id;
        $is_exists =\Es::exists($params);
        if(!$is_exists){
             return \Response::json(array(
            'error' => true,
            'response' => $is_exists),
            404
            );
        }
        $input = json_decode(\Input::get('data', '{}'), TRUE);
        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_POST;
        $queue_message['data'] = array('id' => $id, 'body' => $input);
        $this->push_into_queue($queue_message);
        return \Response::json( ProfileHelper::get_success_response(),
            200
        );
    }

    public function show($id)
    {
        //ProfileHelper::delete_index();
        //ProfileHelper::add_index();exit;
        $params = ProfileHelper::get_elastic_config();
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
            'response' => $results),
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
        $fields = \Input::get('fields' , '');
        $params = ProfileHelper::get_elastic_config();
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
        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_DELETE;
        $queue_message['data'] = array('id' => $id);
        $this->push_into_queue($queue_message);
        return \Response::json( ProfileHelper::get_success_response(),
            200
        );
    }


    public function facets()
    {
        $q = \Input::get('q' , '');
        $size = (int) \Input::get('size' , 10);
        $skip = (int) \Input::get('skip' , 0);
        $field = \Input::get('field' , 'created_date');
        $params = array();
        $params['index'] = 'lcast';
        $params['type']  = 'bench_cast';
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


    public function push_into_queue($profile_params)
    {
        $this->dispatch(new QueueProfile($profile_params));

    }
}
