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
        $params['body']  = (array) ProfileHelper::format_date($data);
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

    public static function format_date($data = NULL){
         if(!$data || !array_get($data, 'id', NULL))
        {
            return $data;
        }
        $data['created_date'] = ProfileHelper::get_formatted_date(array_get($data, 'created_date', time()));
        $data['last_signin'] = ProfileHelper::get_formatted_date(array_get($data, 'last_signin', time()));
        $data['plan_updated_date'] = ProfileHelper::get_formatted_date(array_get($data, 'plan_updated_date', time()));
        $data['mobile'] = str_replace(" ", "", $data['mobile']);
        return $data;
    }
     public static function get_formatted_date($date = NULL){
        if(!$date)
        {
            return date('c', time());
        }
        return date('c', $date);
    }

    public static function delete_index($index = NULL){
        if(!$index){
            $index = ProfileHelper::ELASTIC_INDEX ;
        }
        $deleteParams['index'] = $index;
        $is_exists =\Es::indices()->delete($deleteParams);
    }

     public static function add_index($index = NULL){
        if(!$index){
            $index = ProfileHelper::ELASTIC_INDEX ;
        }
        $params = [
            'index' => $index,
                 'body' => [
                    'settings' => [
                        'number_of_shards' => 1,
                        'number_of_replicas' => 0,
                        'analysis' => [
                            'filter' => [
                                'shingle' => [
                                    'type' => 'shingle'
                                ]
                            ],
                            'char_filter' => [
                                'pre_negs' => [
                                    'type' => 'pattern_replace',
                                    'pattern' => '(\\w+)\\s+((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\b',
                                    'replacement' => '~$1 $2'
                                ],
                                'post_negs' => [
                                    'type' => 'pattern_replace',
                                    'pattern' => '\\b((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\s+(\\w+)',
                                    'replacement' => '$1 ~$2'
                                ]
                            ],
                            'analyzer' => [
                                'reuters' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'standard',
                                    'filter' => ['lowercase', 'stop', 'kstem']
                                ]
                            ]
                        ]
                    ],
                    'mappings' => [
                        '_default_' => [
                            'properties' => [
                                'id' => [
                                    'type' => 'long',
                                    'store' =>  true
                                ],
                                'type' => [
                                    'type' => 'boolean',
                                ],
                                'ref_id' => [
                                    'type' => 'string',
                                    'analyzer' => 'reuters',
                                    'term_vector' => 'yes',
                                    'store' =>  true
                                ],
                                'username' => [
                                    'type' => 'string',
                                    'analyzer' => 'reuters',
                                    'term_vector' => 'yes',
                                    'store' =>  true
                                ],
                                'email' => [
                                    'type' => 'string',
                                    'analyzer' => 'reuters',
                                    'term_vector' => 'yes',
                                    'store' =>  true
                                ],
                                'mobile' => [
                                    'type' => 'double',
                                    'store' =>  true
                                ],
                                'academic_skip_status' => [
                                    'type' => 'boolean',
                                    'store' =>  true
                                ],
                               'experience_skip_status' => [
                                    'type' => 'boolean',
                                    'store' =>  true
                                ],
                                 'profile_complete_status' => [
                                    'type' => 'boolean',
                                    'store' =>  true
                                ],
                                 'plan' => [
                                    'type' => 'boolean',
                                    'store' =>  true
                                ],
                                 'plan_updated_date' => [
                                   'type' => 'date',
                                    'format' => 'dateOptionalTime',
                                    'store' =>  true
                                ],
                                'created_date' => [
                                    'type' => 'date',
                                    'format' => 'dateOptionalTime',
                                    'store' =>  true
                                ],
                                'last_signin' => [
                                    'type' => 'date',
                                    'format' => 'dateOptionalTime',
                                    'store' =>  true
                                ],
                                'updated_date' => [
                                    'type' => 'date',
                                    'format' => 'dateOptionalTime',
                                    'store' =>  true
                                ],
                                 'created_ip' => [
                                    'type' => 'string',
                                    'store' =>  true
                                ],
                                 'updated_by' => [
                                    'type' => 'string',
                                    'store' =>  true
                                ],
                                 'updated_ip' => [
                                    'type' => 'string',
                                    'store' =>  true
                                ],
                                 'last_signin_ip' => [
                                    'type' => 'string',
                                    'store' =>  true
                                ],
                                 'activation_key' => [
                                    'type' => 'string',
                                    'store' =>  true
                                ],
                                 'activation_status' => [
                                    'type' => 'boolean',
                                    'store' =>  true
                                ],
                                 'reset_pwd' => [
                                    'type' => 'boolean',
                                    'store' =>  true
                                ],
                                 'status' => [
                                    'type' => 'boolean',
                                    'store' =>  true
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        $is_exists =\Es::indices()->create($params);
    }
}
