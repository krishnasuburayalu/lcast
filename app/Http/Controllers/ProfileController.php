<?php
namespace LCast\Http\Controllers;

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
use LCast\GeoHelper;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

class ProfileController extends Controller
{
    
    /**
     * Make sure the user is authenticated.
     *
     */
    public function __construct() {
    }
    
    /**
     * landing controller routine
     *
     */
    public function index() {
        $user = BCUser::find(5);
        dd($user);
    }
    
    /**
     * Indexing by profile id - mysql id
     *
     */
    
    public function import() {
        
        // Temporarily increase memory limit to 256MB
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 60);
        $provider = array();
        $config = new LexerConfig();
        $config->setDelimiter("\t");
        $lexer = new Lexer($config);
        
        $interpreter = new Interpreter();
        $fields = array('COMMAND','TYPE','BID','GROUP_NAME','NAME','ADDRESS1','ADDRESS2','CITY','STATE','ZIP','PHONE','FAX','LASTNAME','FIRSTNAME','MI','DEGREE','JCode','NASCO','NOTE','JCODE_PANEL_STATUS','JCODE_PANEL_CODE','LANGUAGE','SSN','TAXID','COUNTY','DataID','PRODUCT_SPECIALTY_HOSPITAL','MGCN','TRAD','STNY','NJPL','MCBL','HDTC','HDDC','HDDP','HDDT','MCNY','VSTANY','NYEP','NYPP','ADVN','ACO','PCMH','MALE','FEMALE','Mon_open1','Mon_close1','Mon_open2','Mon_close2','Tue_open1','Tue_close1','Tue_open2','Tue_close2','Wed_open1','Wed_close1','Wed_open2','Wed_close2','Thu_open1','Thu_close1','Thu_open2','Thu_close2','Fri_open1','Fri_close1','Fri_open2','Fri_close2','Sat_open1','Sat_close1','Sat_open2','Sat_close2','Sun_open1','Sun_close1','Sun_open2','Sun_close2','NATIONALPROVIDERID','NET_START_DATE','DENTEMAX','LUCENT','OFFCODE','ANCTEST','HOSPCODE','HOSP','RPQ','HORIZON_DENTAL_EPO','BOARD_CERTIFIED','SPECIALTY','LATITUDE','LONGITUDE','EHR','QRP','NCQA','HNJH_IND','PRACTICE_LIMITATION','ACPT_NEW_PAT','MCR_RATING','MBPC','BRNB','SHMC','CCX','OMT1','OMT2','OAT1','OST2','OST1','OAT2','OMT1_EFF_DT','OMT1_END_DT','OMT2_EFF_DT','OMT2_END_DT','OAT1_EFF_DT','OAT1_END_DT','OST2_EFF_DT','OST2_END_DT','OST1_EFF_DT','OST1_END_DT','OAT2_EFF_DT','OAT2_END_DT','TRAD_EFF_DT','TRAD_END_DT','MGCN_EFF_DT','MGCN_END_DT','MCBL_EFF_DT','MCBL_END_DT','ADVN_EFF_DT','ADVN_END_DT','ACO_EFF_DT','ACO_END_DT','PCMH_EFF_DT','PCMH_END_DT','MBPC_EFF_DT','MBPC_END_DT','BRNB_EFF_DT','BRNB_END_DT','SHMC_EFF_DT','SHMC_END_DT');
        $fields =array_map('strtolower', $fields);
        $response = array();
        $interpreter->addObserver(function (array $row) use ($fields, &$response) {
            $data = array_combine($fields, $row);
            if (array_get($data, 'bid', FALSE) !== FALSE && array_get($data, 'command', FALSE) !== FALSE && array_get($data, 'type', FALSE) == 'H') {
                $data['processed_date'] = ProfileHelper::get_today_date();
                switch (array_get($data, 'command', 'A')) {
                    case 'A':
                        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_PUT;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $response['profile'][] = array('id' => $data['bid'], 'status' => 'success', 'action' => 'Add');
                        break;

                    case 'D':
                        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_DELETE;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $response['profile'][] = array('id' => $data['bid'], 'status' => 'success', 'action' => 'DEL');
                        break;

                    default:
                        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_PUT;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $response['profile'][] = array('id' => $data['bid'], 'status' => 'success', 'action' => 'Add');
                        return true;
                }
            }
        });

        $lexer->parse(storage_path() . '/source_dir/MDM_COMMERCIALFULL-20150730112521.csv', $interpreter);
        return \Response::json(array('error' => false, 'response' => $response), 200);
    }
    
    /**
     * Indexing by profile id - mysql id
     *
     */
    
    public function add($id) {
        $response = array();
        $user = BCUser::find($id);
        $user->albumes = $user->albums()->get(['type', 'title', 'source', 'description', 'status'])->toArray();
        $user->appearances = $user->appearances()->get(['user_age', 'height_cm', 'height_feet', 'weight_kg', 'weight_pound', 'built', 'hair_style', 'hair_color', 'skin_color', 'eye_color'])->toArray();
        $user->experiences = $user->experiences()->get(['type', 'role', 'title', 'from_date', 'to_date', 'description'])->toArray();
        $user->profile = $user->profile()->get(['name', 'gender', 'dob', 'language', 'state', 'country', 'capability', 'avatar', 'about_me'])->toArray();
        $user->qualifications = $user->qualifications()->get(['type', 'title', 'from_date', 'to_date', 'description'])->toArray();
        
        //Push into queue
        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_PUT;
        $queue_message['data'] = $user->toArray();
        $this->push_into_queue($queue_message);
        $response['profile'][] = array('id' => $user->id, 'status' => 'success');
        if ($user->count() <= 0) {
            return \Response::json(array('error' => true, 'response' => array('error' => true, 'message' => 'profile not found.')), 404);
        }
        return \Response::json(array('error' => false, 'response' => $response), 200);
    }
    
    /**
     * Get profile info from elasticsearch by profile id
     *
     */
    
    public function update($id) {
        $params = ProfileHelper::get_elastic_config();
        $params['id'] = $id;
        $is_exists = \Es::exists($params);
        if (!$is_exists) {
            return \Response::json(array('error' => true, 'response' => $is_exists), 404);
        }
        $input = json_decode(\Input::get('data', '{}'), TRUE);
        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_POST;
        $queue_message['data'] = array('id' => $id, 'body' => $input);
        $this->push_into_queue($queue_message);
        return \Response::json(ProfileHelper::get_success_response(), 200);
    }
    
    public function rebuild_index() {
        ProfileHelper::delete_index();
        ProfileHelper::add_index();
        exit;
    }
    
    public function show($id) {
        $params = ProfileHelper::get_elastic_config();
        $params['id'] = $id;
        $is_exists = \Es::exists($params);
        if (!$is_exists) {
            return \Response::json(array('error' => true, 'response' => $is_exists), 404);
        }
        $params['_source'] = TRUE;
        $results = \Es::get($params);
        return \Response::json(array('error' => false, 'response' => array_get($results, '_source')), 200);
    }
    
    /**
     * Search profiles by Query in the Lucene query string syntax
     *
     */
    
    public function search() {
        $q = \Input::get('q', '');
        $size = (int)\Input::get('size', 10);
        $skip = (int)\Input::get('skip', 0);
        $fields = \Input::get('fields', 'bid,type,network,firstname,lastname,name,phone,county,city,address1,address2,state,zip,zip4,phone,degree,language,mi,state,gender,omt1,omt2,specialties,location');
        $zip = \Input::get('zip', 0);
        $radius = (int)\Input::get('radius', 10);
        $params = ProfileHelper::get_elastic_config();
        if ($fields == '') {
            $params['_source'] = TRUE;
        } 
        else {
            $params['_source_include'] = $fields;
        }
        $filter = array();
        $params['size'] = $size;
        $params['from'] = $skip;
        $params['body']['sort'] = array('name_raw' => array("order" => "asc"));
        if ($zip != 0) {
            $filter['and'] = array();
            $zip_cordinates = GeoHelper::get_geocode($zip, TRUE);
            $geofilter = array('geo_distance' => array('distance' => $radius . 'km', 'location' => $zip_cordinates));;
            $filter['and'][] = $geofilter;
        }
        $params['body']['query']['filtered']["filter"] = $filter;
        $params['body']['query']['filtered']["query"]['bool']['must'] = ProfileHelper::build_filters();
        
        //print_r($params['body']);exit;
        $results = \Es::search($params);
        $count = array_get($results, 'hits.total', 0);
        $newresults = array();
        if ($zip != 0 && $count > 0) {
            foreach ($results['hits']['hits'] as $result) {
                $zip_cordinates = GeoHelper::get_geocode($zip);
                $result['_source']['distance'] = ProfileHelper::distance($zip_cordinates['location']['lat'], $zip_cordinates['location']['lon'], $result['_source']['location']['lat'], $result['_source']['location']['lon']);
                $result['_source']['distance'] = (float)number_format($result['_source']['distance'], 2, '.', '');
                $newresults[] = $result;
            }
            $results['hits']['hits'] = $newresults;
        }
        
        if ($count <= 0) {
            return \Response::json(array('error' => true, 'response' => array('error' => true, 'message' => 'profile not found.'), 'query' => $params), 200);
        }
        return \Response::json(array('error' => false, 'response' => $results['hits']), 200);
    }
    
    public function suggest() {
        $q = \Input::get('q', '');
        $field = \Input::get('field', 'name_suggest');
        $params = ProfileHelper::get_elastic_config();
        unset($params['type']);
        $params['body']['did-you-mean'] = array('text' => $q, "phrase" => array('size' => 5, "field" => $field, "real_word_error_likelihood" => 0.50, "max_errors" => 1.0, "gram_size" => 3));
        $results = \Es::suggest($params);
        $suggestons = array_get($results, 'did-you-mean.0.options', 0);
        return \Response::json(array('error' => false, 'response' => $suggestons), 200);
    }
    
    /**
     * delete profile info from elasticsearch by profile id
     *
     */
    
    public function delete($id) {
        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_DELETE;
        $queue_message['data'] = array('id' => $id);
        $this->push_into_queue($queue_message);
        return \Response::json(ProfileHelper::get_success_response(), 200);
    }
    
    public function facets() {
        $q = \Input::get('q', '');
        $size = (int)\Input::get('size', 10);
        $skip = (int)\Input::get('skip', 0);
        $fields = \Input::get('fcfields', 'language,county');
        $zip = \Input::get('zip', 0);
        $radius = (int)\Input::get('radius', 10);
        $params = ProfileHelper::get_elastic_config();
        $filter = array();
        $params['size'] = $size;
        $params['from'] = $skip;
        if ($zip != 0) {
            $filter['and'] = array();
            $zip_cordinates = GeoHelper::get_geocode($zip, TRUE);
            $geofilter = array('geo_distance' => array('distance' => $radius . 'km', 'location' => $zip_cordinates));;
            $filter['and'][] = $geofilter;
        }
        $field_parts = explode(',', $fields);
        $field = array();
        foreach ($field_parts as $f) {
            $field[$f] = array('terms' => array('field' => $f, 'size' => 10));
        }
        $params['body']['query']['filtered']["filter"] = $filter;
        $params['body']['query']['filtered']["query"]['bool']['must'] = ProfileHelper::build_filters();;
        $params['body']["facets"] = $field;
        $results = \Es::search($params);
        $count = array_get($results, 'hits.total', 0);
        if ($count <= 0) {
            return \Response::json(array('error' => true, 'response' => array('error' => true, 'message' => 'profile not found.')), 404);
        }
        return \Response::json(array('error' => false, 'response' => $results['facets']), 200);
    }
    
    public function push_into_queue($profile_params) {
        $this->dispatch(new QueueProfile($profile_params));
    }
}
