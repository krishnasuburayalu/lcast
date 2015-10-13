<?php namespace LCast\Commands;

use LCast\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Elasticsearch\Client;
use LCast\ProfileHelper;
use LCast\GeoHelper;

class QueueGeocode extends Command implements SelfHandling, ShouldBeQueued {

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
		$action  = array_get($this->message, 'action', GeoHelper::QUEUE_METHOD_GET);
		$data  = array_get($this->message, 'data', NULL);
		if($data){
			switch($action){
				case GeoHelper::QUEUE_METHOD_POST:
					GeoHelper::update_geocode($data);
				break;
				case GeoHelper::QUEUE_METHOD_PUT:
					GeoHelper::add_geocode($data);
				break;
				case GeoHelper::QUEUE_METHOD_DELETE:
					GeoHelper::delete_geocode($data);
				break;
				default:
					GeoHelper::get_geocode($data);
				return true;
			}
		}
		return true;
	}

}
