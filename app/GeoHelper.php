<?php namespace LCast;

use Exception;
use LCast\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Queue\Capsule\Manager as Queue;
use Pheanstalk\Pheanstalk;
use Elasticsearch\Client;


class GeoHelper {

	const QUEUE_METHOD_GET = 'get';
    const QUEUE_METHOD_POST = 'post';
    const QUEUE_METHOD_DELETE = 'delete';
    const QUEUE_METHOD_PUT = 'put';

    //queue params
    const ELASTIC_INDEX = 'dde_geo';
    const ELASTIC_TYPE = 'geo';

    //response messages
    const ACTION_SUCCESS_MESSAGE = 'success';

    public static function get_elastic_config($index = NULL , $type = NULL){
        return array(
            'index' => !($index) ? GeoHelper::ELASTIC_INDEX : $index ,
            'type' => !($type) ? GeoHelper::ELASTIC_TYPE : $type ,
            );
    }

    public static function get_success_response($error = TRUE , $message = NULL, $data = array()){
        return array(
            'error' => $error,
            'message' => (!$message) ? GeoHelper::ACTION_SUCCESS_MESSAGE : $message,
            'data' => $data
            );
    }

    public static function add_geocode($data = NULL)
    {
        if(!$data || !array_get($data, 'postal', NULL))
        {
            throw new Exception("Error Processing Request", 1);
        }
        $params = GeoHelper::get_elastic_config();
        $data = GeoHelper::format_location($data);
        ksort($data);
        $params['body']  = $data;
        $params['id']    = array_get($data, 'postal');
         return  \Es::index($params);
    }

     public static function format_location($data = NULL){
         if(!$data || !array_get($data, 'postal', NULL))
        {
            return $data;
        }
        if(array_get($data, 'latitude', NULL) && array_get($data, 'longitude', NULL)){
            $data['location']['lat'] = array_get($data, 'latitude', NULL);
            $data['location']['lon'] = array_get($data, 'longitude', NULL);
        }
        return $data;
    }

    public static function delete_geocode($data = NULL)
    {
        if(!$data || !array_get($data, 'postal', NULL))
        {
            throw new Exception("Error Processing Request", 1);
        }
        $params = GeoHelper::get_elastic_config();
        $params['id']    = (int) array_get($data, 'postal');
        return \Es::delete($params);
    }

    public static function update_geocode($data = NULL)
    {
        if(!$data || !array_get($data, 'postal', NULL))
        {
            throw new Exception("Error Processing Request", 1);
        }
        $params = GeoHelper::get_elastic_config();
        $params['body']['doc']  = array_get($data, 'body', array());
        $params['id']    = (int) array_get($data, 'postal');
        return \Es::update($params);
    }

    public static function get_geocode($zip = NULL, $cordinates_only = FALSE){
         if(!$zip || $zip =='')
        {
            return NULL;
        }
        $params = GeoHelper::get_elastic_config();
        $params['id']    = $zip;
        $data = \Es::get($params);
        return ($cordinates_only == TRUE) ? array_get($data, '_source.location.lat') . "," . array_get($data, '_source.location.lon') :  array_get($data, '_source');
    }
     public static function get_formatted_date($date = NULL){
        if(!$date || $date =='')
        {
            return NULL;
        }
        return \DateTime::createFromFormat('Y/m/d', $date)->format('c');
    }

    public static function get_today_date(){
        return date('c');
    }
    public static function delete_index($index = NULL){
        if(!$index){
            $index = GeoHelper::ELASTIC_INDEX ;
        }
        $deleteParams['index'] = $index;
        $is_exists =\Es::indices()->delete($deleteParams);
    }

    public static function add_index($index = NULL){
        if(!$index){
            $index = GeoHelper::ELASTIC_INDEX ;
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
                                    'type' => 'string',
                                    'index' => 'not_analyzed',
                                    'store' =>  true
                                ],
                                'city' => [
                                    'type' => 'string',
                                    'analyzer' => 'reuters',
                                    'term_vector' => 'yes',
                                    'store' =>  true
                                ],
                                'latitude' => [
                                    'type' => 'long',
                                    'store' =>  true
                                ],
                                'longitude' => [
                                    'type' => 'long',
                                    'store' =>  true
                                ],
                                'postal' => [
                                    'type' => 'string',
                                    'analyzer' => 'reuters',
                                    'term_vector' => 'yes',
                                    'store' =>  true
                                ],
                                'address2' => [
                                    'type' => 'string',
                                    'analyzer' => 'reuters',
                                    'term_vector' => 'yes',
                                    'store' =>  true
                                ],
                                'state' => [
                                    'type' => 'string',
                                    'analyzer' => 'reuters',
                                    'term_vector' => 'yes',
                                    'store' =>  true
                                ],
                                 'processed_date' => [
                                    'type' => 'date',
                                    'format' => 'dateOptionalTime',
                                    'store' =>  true
                                ],
                                 'location' => [
                                    'type' => 'geo_point',
                                    'store' =>  true
                                ],
                            ]
                        ]
                    ]
                ]
            ];
        $is_exists =\Es::indices()->create($params);
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::                                                                         :*/
    /*::  This routine calculates the distance between two points (given the     :*/
    /*::  latitude/longitude of those points). It is being used to calculate     :*/
    /*::  the distance between two locations using GeoDataSource(TM) Products    :*/
    /*::                                                                         :*/
    /*::  Definitions:                                                           :*/
    /*::    South latitudes are negative, east longitudes are positive           :*/
    /*::                                                                         :*/
    /*::  Passed to function:                                                    :*/
    /*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
    /*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
    /*::    unit = the unit you desire for results                               :*/
    /*::           where: 'M' is statute miles                                   :*/
    /*::                  'K' is kilometers (default)                            :*/
    /*::                  'N' is nautical miles                                  :*/
    /*::  Worldwide cities and other features databases with latitude longitude  :*/
    /*::  are available at http://www.geodatasource.com                          :*/
    /*::                                                                         :*/
    /*::  For enquiries, please contact sales@geodatasource.com                  :*/
    /*::                                                                         :*/
    /*::  Official Web site: http://www.geodatasource.com                        :*/
    /*::                                                                         :*/
    /*::         GeoDataSource.com (C) All Rights Reserved 2014                  :*/
    /*::                                                                         :*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit = "M") {

      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
            return $miles;
          }
    }

}
