<?php namespace LCast\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Http\Request;

class CallRoute extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'route:call';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Call route from CLI.';

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
		$request = Request::create($this->option('uri'), 'GET');
        $this->info(app()['Illuminate\Contracts\Http\Kernel']->handle($request));
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
            ['uri', null, InputOption::VALUE_REQUIRED, 'The path of the route to be called', null],
        ];
	}

}
