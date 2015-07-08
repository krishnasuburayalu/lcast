<?php namespace LCast\Commands;

use LCast\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Elasticsearch\Client;
use LCast\ProfileHelper;

class QueueProfile extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	protected $message;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($message)
	{
		$this->message = $message;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$action  = array_get($this->message, 'action', ProfileHelper::QUEUE_METHOD_GET);
		$data  = array_get($this->message, 'data', NULL);
		if($data){
			switch($action){
				case ProfileHelper::QUEUE_METHOD_POST:
					ProfileHelper::update_profile($data);
				break;
				case ProfileHelper::QUEUE_METHOD_PUT:
					ProfileHelper::add_profile($data);
				break;
				case ProfileHelper::QUEUE_METHOD_DELETE:
					ProfileHelper::delete_profile($data);
				break;
				default:
					ProfileHelper::get_profile($data);
				return true;
			}
		}
		return true;
	}

}
