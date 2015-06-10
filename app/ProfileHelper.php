<?php namespace LCast;

use Exception;
use LCast\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Queue\Capsule\Manager as Queue;
use Pheanstalk\Pheanstalk;
use Elasticsearch\Client;


class ProfileHelper {

	const QUEUE_METHOD_GET = 'get';
    const QUEUE_METHOD_POST = 'post';
    const QUEUE_METHOD_DELETE = 'delete';
    const QUEUE_METHOD_PUT = 'put';

    //queue params
    const ELASTIC_INDEX = 'lcast';
    const ELASTIC_TYPE = 'bench_cast';

    //response messages
    const ACTION_SUCCESS_MESSAGE = 'success';

    public static function get_elastic_config($index = NULL , $type = NULL){
        return array(
            'index' => !($index) ? ProfileHelper::ELASTIC_INDEX : $index ,
            'type' => !($type) ? ProfileHelper::ELASTIC_TYPE : $type ,
            );
    }

    public static function get_success_response($error = TRUE , $message = NULL, $data = array()){
        return array(
            'error' => $error,
            'message' => (!$message) ? ProfileHelper::ACTION_SUCCESS_MESSAGE : $message,
            'data' => $data
            );
    }

    public static function add_profile($data = NULL)
    {
        if(!$data || !array_get($data, 'id', NULL))
        {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $params['body']  = (array) $data;
        $params['id']    = (int) array_get($data, 'id');
        return  \Es::index($params);
    }

    public static function delete_profile($data = NULL)
    {
        if(!$data || !array_get($data, 'id', NULL))
        {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $params['id']    = (int) array_get($data, 'id');
        return \Es::delete($params);
    }

    public static function update_profile($data = NULL)
    {
        if(!$data || !array_get($data, 'id', NULL))
        {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $params['body']['doc']  = array_get($data, 'body', array());
        $params['id']    = (int) array_get($data, 'id');
        return \Es::update($params);
    }

    public static function get_profile($data = NULL){
         if(!$data || !array_get($data, 'id', NULL))
        {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $params['body']  = (array) $data;
        $params['id']    = (int) array_fetch($data, 'id');
        return \Es::get($params);
    }

}
