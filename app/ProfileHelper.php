<?php
namespace LCast;

use Exception;
use LCast\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Queue\Capsule\Manager as Queue;
use Pheanstalk\Pheanstalk;
use Elasticsearch\Client;

class ProfileHelper
{

    const QUEUE_METHOD_GET = 'get';
    const QUEUE_METHOD_POST = 'post';
    const QUEUE_METHOD_DELETE = 'delete';
    const QUEUE_METHOD_PUT = 'put';

    //queue params
    const ELASTIC_INDEX = 'dde';
    const ELASTIC_TYPE = 'providers';

    //response messages
    const ACTION_SUCCESS_MESSAGE = 'success';

    public static function get_elastic_config($index = NULL, $type = NULL) {
        return array(
            'index' => !($index) ? ProfileHelper::ELASTIC_INDEX : $index,
            'type' => !($type) ? ProfileHelper::ELASTIC_TYPE : $type,
        );
    }

    public static function get_success_response($error = TRUE, $message = NULL, $data = array()) {
        return array(
            'error' => $error,
            'message' => (!$message) ? ProfileHelper::ACTION_SUCCESS_MESSAGE : $message,
            'data' => $data
        );
    }

    public static function add_profile($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $data = ProfileHelper::format_bool($data);
        $data = ProfileHelper::format_network($data);
        $data = ProfileHelper::format_location($data);
        $data = ProfileHelper::format_specialty($data);
        $data = ProfileHelper::format_date($data);
        $data = ProfileHelper::format_zip($data);
        $data = ProfileHelper::format_name($data);
        $data = ProfileHelper::format_gender($data);
        $data = ProfileHelper::format_clean($data);
        ksort($data);
        $params['body'] = $data;
        $params['id'] = array_get($data, 'bid');
        return \Es::index($params);
    }
    public static function format_clean($data = NULL) {
        unset($data['ssn'], $data['taxid']);
        return $data;
    }
    public static function delete_profile($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $params['id'] = (int)array_get($data, 'bid');
        return \Es::delete($params);
    }

    public static function update_profile($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $params['body']['doc'] = array_get($data, 'body', array());
        $params['id'] = (int)array_get($data, 'bid');
        return \Es::update($params);
    }

    public static function get_profile($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            throw new Exception("Error Processing Request", 1);
        }
        $params = ProfileHelper::get_elastic_config();
        $params['body'] = (array)$data;
        $params['id'] = (int)array_fetch($data, 'bid');
        return \Es::get($params);
    }
    public static function format_gender($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            return $data;
        }
        $data['gender'] = 'u';
        if (array_get($data, 'male', NULL) || array_get($data, 'female', NULL)) {
            if (array_get($data, 'male', FALSE) == FALSE) {
                $data['gender'] = 'male';
            }
            else {
                $data['gender'] = 'female';
            }
        }
        unset($data['male'], $data['female']);
        return $data;
    }

    public static function format_location($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            return $data;
        }
        if (array_get($data, 'latitude', NULL) && array_get($data, 'longitude', NULL)) {
            $data['location']['lat'] = array_get($data, 'latitude', NULL);
            $data['location']['lon'] = array_get($data, 'longitude', NULL);
            unset($data['latitude'], $data['longitude']);
        }
        return $data;
    }

    public static function format_name($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            return $data;
        }

        if (array_get($data, 'type', NULL) == 'D') {
            $data['name'] = array_get($data, 'lastname', '') . ' ' . array_get($data, 'firstname', '') . ' ' . array_get($data, 'mi', '');
        }
        $data['name'] = trim($data['name']);
        $data['name_auto'] = trim($data['name']);
        $data['name_raw'] = trim($data['name']);
        $data['name_suggest'] = trim($data['name']);
        return $data;
    }

    public static function format_zip($data = NULL) {
        if (!$data || !array_get($data, 'zip', NULL)) {
            return $data;
        }
        $zip_parts = explode('-', array_get($data, 'zip'));
        $data['zip'] = (int)array_get($zip_parts, '0', NULL);
        $data['zip4'] = (int)array_get($zip_parts, '1', NULL);
        return $data;
    }

    public static function format_specialty($data = NULL) {
        if (!$data) {
            return $data;
        }
        $data['specialties'] = array();
        $data['hospitals'] = array();
        if (array_get($data, 'type', NULL) == 'D') {
            $specialties = explode(' ', array_get($data, 'product_specialty_hospital', array()));
            foreach ($specialties as $specialty) {
                $flg = strtoupper(substr($specialty, 0, 1));
                if ($flg == 'S') {
                    $specialty = str_replace('S', '', $specialty);
                    $specialty = str_replace('BC', '', $specialty);
                    $data['specialties'][] = array_get(config('provider.specialty_mapping'), $specialty, '');
                }
                if ($flg == 'H') {
                    $data['hospitals'][] = $specialty;
                }
            }
        }
        if (array_get($data, 'type', NULL) == 'S') {
            if (array_get($data, 'specialty', '') == 'HOSPITAL' || array_get($data, 'specialty', '') == 'HOSPITAL-NONPAR') {
                $data['type'] = 'H';
                $data['specialties'] = array();
            }
            else {
                $data['specialties'] = explode(',', array_get($data, 'specialty', array()));
            }
        }
        $data['specialties_auto'] = $data['specialties'];
        $data['specialties_suggest'] = $data['specialties'];
        unset($data['product_specialty_hospital'], $data['specialty']);
        return $data;
    }
    public static function format_network($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            return $data;
        }
        $network_needs_to_be_formatted = config('provider.network_needs_to_be_formatted');
        foreach ($network_needs_to_be_formatted as $key) {
            if (array_get($data, $key, 'F') != 'F' && array_get($data, $key, 'F') != '') {
                $data['network'][] = $key;
                $data[ $key ] = true;
            }
            else {
                unset($data[ $key ]);
            }
        }
        return $data;
    }

    public static function format_date($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            return $data;
        }
        $dates_needs_to_be_formatted = config('provider.dates_needs_to_be_formatted');
        foreach ($dates_needs_to_be_formatted as $key) {
            if (array_get($data, $key) != '') {
                $data[ $key ] = ProfileHelper::get_formatted_date(array_get($data, $key, NULL));
            }
            else {
                unset($data[ $key ]);
            }
        }
        return $data;
    }
    public static function get_formatted_date($date = NULL) {
        if (!$date || $date == '') {
            return NULL;
        }
        return \DateTime::createFromFormat('Y/m/d', $date)->format('c');
    }

    public static function get_today_date() {
        return date('c');
    }

    public static function format_bool($data = NULL) {
        if (!$data || !array_get($data, 'bid', NULL)) {
            return $data;
        }
        $bool_needs_to_be_formatted = config('provider.bool_needs_to_be_formatted');
        foreach ($data as $key => $val) {
            if (in_array($key, $bool_needs_to_be_formatted)) {
                $data[ $key ] = array_get($data, $key, 'F');
                $data[ $key ] = (bool)($data[ $key ] == 'T') ? TRUE : FALSE;
            }
        }
        return $data;
    }

    public static function build_filters($input = array()) {
        $parms_not_to_be_considered = config('provider.parms_not_to_be_considered');
        $parms_to_be_considered = config('provider.parms_to_be_considered');
        $filter = array();
        foreach (\Input::all() as $key => $val) {
            if (in_array($key, $parms_to_be_considered)) {
                $filter[] = array(
                    'match' => array(
                        $key => $val
                    )
                );
            }
        }
        return $filter;
    }

    public static function build_sorting() {
        $sorts = explode(',', \Input::get('sort', 'name_raw'));
        $sort_orders = explode(',', \Input::get('sort_order', 'asc'));
        $i = 0;
        $sort_param = array();
        foreach ($sorts as $sort) {
            $sort_param[] = array(
                $sort => array(
                    "order" => array_get($sort_orders, $i, 'asc')
                )
            );
            $i++;
        }
        return $sort_param;
    }

    public static function delete_index($index = NULL) {
        if (!$index) {
            $index = ProfileHelper::ELASTIC_INDEX;
        }
        $deleteParams['index'] = $index;
        $is_exists = \Es::indices()->delete($deleteParams);
    }

    public static function add_index($index = NULL) {
        if (!$index) {
            $index = ProfileHelper::ELASTIC_INDEX;
        }
        $params = ['index' => $index, 'body' => ['settings' => ['number_of_shards' => 1, 'number_of_replicas' => 0, 'analysis' => ['filter' => ['shingle' => ['type' => 'shingle'], 'ngram' => ['type' => 'ngram', "min_gram" => 3, "max_gram" => 15], 'ngram' => ['type' => 'ngram', "min_gram" => 3, "max_gram" => 15], "english_stop" => ["type" => "stop", "stopwords" => "_english_"], "english_possessive_stemmer" => ["type" => "stemmer", "language" => "possessive_english"], "english_stemmer" => ["type" => "stemmer", "language" => "english"]], 'char_filter' => ['pre_negs' => ['type' => 'pattern_replace', 'pattern' => '(\\w+)\\s+((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\b', 'replacement' => '~$1 $2'], 'post_negs' => ['type' => 'pattern_replace', 'pattern' => '\\b((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\s+(\\w+)', 'replacement' => '$1 ~$2']], 'analyzer' => ['reuters' => ['type' => 'custom', 'tokenizer' => 'standard', 'filter' => ['lowercase', 'stop', 'kstem']], "autocomplete" => ["type" => "custom", "tokenizer" => "standard", "filter" => ["standard", "lowercase", "stop", "kstem", "ngram"]], "suggest" => ["type" => "custom", "tokenizer" => "standard", "filter" => ["english_possessive_stemmer", "lowercase", "english_stop", "english_stemmer"]]]]], 'mappings' => ['_default_' => ['properties' => ['id' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'type' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'group_name' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'group_name_auto' => ['type' => 'string', 'analyzer' => 'autocomplete', 'store' => true], 'name_raw' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'name_suggest' => ['type' => 'string', "payloads" => true, 'analyzer' => 'suggest', ], 'name_auto' => ['type' => 'string', 'analyzer' => 'autocomplete', 'store' => true], 'name' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'address1' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'address2' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'city' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'state' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'zip' => ['type' => 'long', 'store' => true], 'zip4' => ['type' => 'long', 'store' => true], 'phone' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'fax' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'lastname' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'firstname' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'mi' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'degree' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'ssn' => ['type' => 'long', 'store' => false], 'taxid' => ['type' => 'long', 'store' => true], 'county' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'hospitals' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'specialties' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'specialties_auto' => ['type' => 'string', 'analyzer' => 'autocomplete', 'store' => true], 'specialties_suggest' => ['type' => 'string', "payloads" => true, 'analyzer' => 'suggest', ], 'mon_open1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'mon_close1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'mon_open2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'mon_close2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'tue_open1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'tue_close1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'tue_open2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'tue_close2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'wed_open1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'wed_close1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'wed_open2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'thu_open1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'thu_close1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'thu_open2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'thu_close2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'fri_open1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'fri_close1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'fri_open2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'fri_close2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'sat_open1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'sat_close1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'sat_open2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'sat_close2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'sun_open1' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'sun_open2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'sun_close2' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'practice_limitation' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], 'specialty' => ['type' => 'string', 'analyzer' => 'reuters', 'term_vector' => 'yes', 'store' => true], 'network.mgcn' => ['type' => 'boolean', 'store' => true], 'network.trad' => ['type' => 'boolean', 'store' => true], 'network.stny' => ['type' => 'boolean', 'store' => true], 'network.njpl' => ['type' => 'boolean', 'store' => true], 'network.hdtc' => ['type' => 'boolean', 'store' => true], 'network.hddc' => ['type' => 'boolean', 'store' => true], 'network.hddp' => ['type' => 'boolean', 'store' => true], 'network.hddt' => ['type' => 'boolean', 'store' => true], 'network.mcny' => ['type' => 'boolean', 'store' => true], 'network.vstany' => ['type' => 'boolean', 'store' => true], 'network.nyep' => ['type' => 'boolean', 'store' => true], 'network.nypp' => ['type' => 'boolean', 'store' => true], 'network.advn' => ['type' => 'boolean', 'store' => true], 'network.aco' => ['type' => 'boolean', 'store' => true], 'network.pcmh' => ['type' => 'boolean', 'store' => true], 'male' => ['type' => 'boolean', 'store' => true], 'female' => ['type' => 'boolean', 'store' => true], 'dentemax' => ['type' => 'boolean', 'store' => true], 'lucent' => ['type' => 'boolean', 'store' => true], 'offcode' => ['type' => 'boolean', 'store' => true], 'anctest' => ['type' => 'boolean', 'store' => true], 'hospcode' => ['type' => 'boolean', 'store' => true], 'hosp' => ['type' => 'boolean', 'store' => true], 'rpq' => ['type' => 'boolean', 'store' => true], 'horizon_dental_epo' => ['type' => 'boolean', 'store' => true], 'board_certified' => ['type' => 'boolean', 'store' => true], 'ehr' => ['type' => 'boolean', 'store' => true], 'qrp' => ['type' => 'boolean', 'store' => true], 'ncqa' => ['type' => 'boolean', 'store' => true], 'hnjh_ind' => ['type' => 'boolean', 'store' => true], 'practice_limitation' => ['type' => 'boolean', 'store' => true], 'acpt_new_pat' => ['type' => 'boolean', 'store' => true], 'mcr_rating' => ['type' => 'boolean', 'store' => true], 'network.mbpc' => ['type' => 'boolean', 'store' => true], 'network.brnb' => ['type' => 'boolean', 'store' => true], 'ccx' => ['type' => 'boolean', 'store' => true], 'network.omt1' => ['type' => 'boolean', 'store' => true], 'network.omt2' => ['type' => 'boolean', 'store' => true], 'network.oat1' => ['type' => 'boolean', 'store' => true], 'network.ost2' => ['type' => 'boolean', 'store' => true], 'network.ost1' => ['type' => 'boolean', 'store' => true], 'network.oat2' => ['type' => 'boolean', 'store' => true], 'network.shmc' => ['type' => 'boolean', 'store' => true], 'location' => ['type' => 'geo_point', 'store' => true], 'net_start_date' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'omt1_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'omt1_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'omt2_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'omt2_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'oat1_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'oat1_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'ost2_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'ost2_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'ost1_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'ost1_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'oat2_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'oat2_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'trad_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'trad_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'mgcn_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'mgcn_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'mcbl_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'mcbl_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'advn_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'advn_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'aco_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'aco_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'pcmh_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'pcmh_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'mbpc_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'mbpc_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'brnb_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'brnb_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'shmc_eff_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'shmc_end_dt' => ['type' => 'date', 'format' => 'dateOptionalTime', 'store' => true], 'gender' => ['type' => 'string', 'index' => 'not_analyzed', 'store' => true], ]]]]];
        $is_exists = \Es::indices()->create($params);
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
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        }
        else if ($unit == "N") {
            return ($miles * 0.8684);
        }
        else {
            return $miles;
        }
    }
}
