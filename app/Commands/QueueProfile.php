<?php namespace LCast\Commands;

use LCast\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Elasticsearch\Client;

class QueueProfile extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	protected $profile;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($profile)
	{
		$this->profile = $profile;//
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
	    echo 'Indexing user: ' . $this->profile['id'] .'\n'; 
            $params = array();
            $params['body']  = (array)$this->profile;
            $params['index'] = 'lcast';
            $params['type']  = 'bench_cast';
            $params['id']    = $this->profile['id'];
            $ret = \Es::index($params);
	    return true;
	}

}
