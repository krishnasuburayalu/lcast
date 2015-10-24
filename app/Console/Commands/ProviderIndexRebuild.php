<?php namespace LCast\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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

class ProviderIndexRebuild extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'provider:indexrebuild';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete current index and rebuild.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Deleting existing index :' . json_encode(ProfileHelper::get_elastic_config()));
		ProfileHelper::delete_index();
		$this->info('Rebuilding index with new mappings.');
        ProfileHelper::add_index();
        $this->info('Done.');
	}
}
