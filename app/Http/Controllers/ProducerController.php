<?php namespace LCast\Http\Controllers;

use LCast\Http\Requests;
use LCast\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Queue\Capsule\Manager as Queue;
use LCast\Todolist;
use LCast\User;
use LCast\Task;
use LCast\Http\Requests\TaskCreateFormRequest;
use LCast\Commands\SendEmail;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pheanstalk\Pheanstalk;
use Elasticsearch\Client;
 

class ProducerController extends Controller {

    /**
     * Make sure the user is authenticated.
     * 
     */
    public function __construct()
    {
       
    }
public function index()
    {
        $this->dispatch(new SendEmail(time()));
        //return view('welcome ');
       // Queue::push('SendEmail', array('message' => time()), 'lcast', $pheanstalk);
    
    }

    public function addIndex(){
        $params = array();
        $params['body']  = array('testField' => 'abc');
        $params['index'] = 'my_index';
        $params['type']  = 'my_type';
        $params['id']    = '1';
        $ret = \Es::index($params);
    }
 
}
