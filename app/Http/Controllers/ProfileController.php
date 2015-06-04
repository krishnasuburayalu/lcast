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
    public function index()
    {
         $user = BCUser::find(5);
         dd($user);
    }

    public function show($id)
    {
        $user = BCUser::find($id);
        $user->albumes =  $user->albums()->get()->toArray();
        $user->appearances =  $user->appearances()->get()->toArray();
        $user->experiences =  $user->experiences()->get()->toArray();
        $user->profile =  $user->profile()->get()->toArray();
        $user->qualifications =  $user->qualifications()->get()->toArray();

        //Push into queue
        $this->push_into_queue($user->toArray());
        print_r( $user->toArray());
    }

    public function push_into_queue($profile_params)
    {
        $this->dispatch(new QueueProfile($profile_params));

    }

    public function addIndex(){

        $users =  \DB::table('userInfo')->skip(10)->take(100)->get();
        foreach ($users as $user)
        {
            echo 'Indexing user: ' . $user->id .'\n'; 
            $params = array();
            $params['body']  = (array)$user;
            $params['index'] = 'lcast';
            $params['type']  = 'userinfo';
            $params['id']    = $user->id;
            $ret = \Es::index($params);
        }
    }
 
}
