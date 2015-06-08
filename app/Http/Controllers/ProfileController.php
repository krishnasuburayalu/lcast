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
        $users = BCUser::take($size )->skip($start)->get();
        $response['count'] = $users->count();
        foreach($users as $user){
            $user->albumes =  $user->albums()->get()->toArray();
            $user->appearances =  $user->appearances()->get()->toArray();
            $user->experiences =  $user->experiences()->get()->toArray();
            $user->profile =  $user->profile()->get()->toArray();
            $user->qualifications =  $user->qualifications()->get()->toArray();
            //Push into queue
            $this->push_into_queue($user->toArray());
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
        $user->albumes =  $user->albums()->get()->toArray();
        $user->appearances =  $user->appearances()->get()->toArray();
        $user->experiences =  $user->experiences()->get()->toArray();
        $user->profile =  $user->profile()->get()->toArray();
        $user->qualifications =  $user->qualifications()->get()->toArray();
        //Push into queue
        $this->push_into_queue($user->toArray());
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

    public function show($id)
    {
        $params = array();
        $params['index'] = 'lcast';
        $params['type']  = 'bench_cast';
        $params['id']  = $id;
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
        $params = array();
        $params['index'] = 'lcast';
        $params['type']  = 'bench_cast';
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
     * Get profile info from elasticsearch by profile id
     * 
     */

    public function delete($id)
    {
        $params = array();
        $params['index'] = 'lcast';
        $params['type']  = 'bench_cast';
        $params['id']  = $id;
        $results =\Es::delete($params);
        return \Response::json(array(
            'error' => false,
            'response' => $results),
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
        $params['body']['query']['query_string']['query']  = $q;
        $params['body']['facets']['facets']['terms']['field']  = $field;
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


    public function push_into_queue($profile_params)
    {
        $this->dispatch(new QueueProfile($profile_params));

    }
}
