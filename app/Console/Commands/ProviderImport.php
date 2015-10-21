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


class ProviderImport extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'provider:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import providers form CSV file.';

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
		$file_path = $this->option('file');
		// Temporarily increase memory limit to 256MB
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);
        $provider = array();
        $config = new LexerConfig();
        $config->setDelimiter("\t");
        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        $fields = array('COMMAND','TYPE','BID','GROUP_NAME','NAME','ADDRESS1','ADDRESS2','CITY','STATE','ZIP','PHONE','FAX','LASTNAME','FIRSTNAME','MI','DEGREE','JCode','NASCO','NOTE','JCODE_PANEL_STATUS','JCODE_PANEL_CODE','LANGUAGE','SSN','TAXID','COUNTY','DataID','PRODUCT_SPECIALTY_HOSPITAL','MGCN','TRAD','STNY','NJPL','MCBL','HDTC','HDDC','HDDP','HDDT','MCNY','VSTANY','NYEP','NYPP','ADVN','ACO','PCMH','MALE','FEMALE','Mon_open1','Mon_close1','Mon_open2','Mon_close2','Tue_open1','Tue_close1','Tue_open2','Tue_close2','Wed_open1','Wed_close1','Wed_open2','Wed_close2','Thu_open1','Thu_close1','Thu_open2','Thu_close2','Fri_open1','Fri_close1','Fri_open2','Fri_close2','Sat_open1','Sat_close1','Sat_open2','Sat_close2','Sun_open1','Sun_close1','Sun_open2','Sun_close2','NATIONALPROVIDERID','NET_START_DATE','DENTEMAX','LUCENT','OFFCODE','ANCTEST','HOSPCODE','HOSP','RPQ','HORIZON_DENTAL_EPO','BOARD_CERTIFIED','SPECIALTY','LATITUDE','LONGITUDE','EHR','QRP','NCQA','HNJH_IND','PRACTICE_LIMITATION','ACPT_NEW_PAT','MCR_RATING','MBPC','BRNB','SHMC','CCX','OMT1','OMT2','OAT1','OST2','OST1','OAT2','OMT1_EFF_DT','OMT1_END_DT','OMT2_EFF_DT','OMT2_END_DT','OAT1_EFF_DT','OAT1_END_DT','OST2_EFF_DT','OST2_END_DT','OST1_EFF_DT','OST1_END_DT','OAT2_EFF_DT','OAT2_END_DT','TRAD_EFF_DT','TRAD_END_DT','MGCN_EFF_DT','MGCN_END_DT','MCBL_EFF_DT','MCBL_END_DT','ADVN_EFF_DT','ADVN_END_DT','ACO_EFF_DT','ACO_END_DT','PCMH_EFF_DT','PCMH_END_DT','MBPC_EFF_DT','MBPC_END_DT','BRNB_EFF_DT','BRNB_END_DT','SHMC_EFF_DT','SHMC_END_DT');
        $fields =array_map('strtolower', $fields);
        $response = array();
        $this->info('Started Processing file :' . $file_path );
        $count = 0;
        $interpreter->addObserver(function (array $row) use ($fields, &$response, &$count) {
            $data = array_combine($fields, $row);
            if (array_get($data, 'bid', FALSE) !== FALSE && array_get($data, 'command', FALSE) !== FALSE && array_get($data, 'type', FALSE) == 'S') {
                $data['processed_date'] = ProfileHelper::get_today_date();
                switch (array_get($data, 'command', 'A')) {
                    case 'A':
                        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_PUT;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $this->info('Processed '. $queue_message['action'].' - BID: ' .$data['bid']);
                        $count++;
                        break;

                    case 'D':
                        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_DELETE;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $this->info('Processed '. $queue_message['action'].' - BID: ' .$data['bid']);
                        $count++;
                        break;

                    default:
                        $queue_message['action'] = ProfileHelper::QUEUE_METHOD_PUT;
                        $queue_message['data'] = $data;
                        $this->push_into_queue($queue_message);
                        $this->info('Processed '. $queue_message['action'].' - BID: ' .$data['bid']);
                        $count++;
                        return true;
                }
            }
        });
        $lexer->parse($file_path, $interpreter);
        $this->info('Process completed total# ' . $count);

	}


	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['file', null, InputOption::VALUE_REQUIRED, 'CSV File path', null],
		];
	}

	 public function push_into_queue($profile_params) {
        app('Illuminate\Bus\Dispatcher')->dispatch(new QueueProfile($profile_params));
    }

}
