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

	/*
				Array
				(
				    [command] => A
				    [type] => D
				    [bid] => 12042621|78021863855220
				    [group_name] => JANE H GLASSMAN PHD
				    [name] => JANE H GLASSMAN PHD
				    [address1] => 1101 KINGS HWY N STE 307
				    [address2] => 
				    [city] => CHERRY HILL
				    [state] => NJ
				    [zip] => 08034-1912
				    [phone] => 8564828929
				    [fax] => 
				    [lastname] => GLASSMAN
				    [firstname] => JANE
				    [mi] => H
				    [degree] => PHD
				    [jcode] => 
				    [nasco] => 78021863855220
				    [note] => 
				    [jcode_panel_status] => 
				    [jcode_panel_code] => 
				    [language] => 
				    [ssn] => 813614478
				    [taxid] => 186385522
				    [county] => Camden
				    [dataid] =>  
				    [product_specialty_hospital] => S304 
				    [mgcn] => T
				    [trad] => T
				    [stny] => F
				    [njpl] => F
				    [mcbl] => T
				    [hdtc] => F
				    [hddc] => F
				    [hddp] => F
				    [hddt] => F
				    [mcny] => F
				    [vstany] => F
				    [nyep] => F
				    [nypp] => F
				    [advn] => T
				    [aco] => F
				    [pcmh] => F
				    [male] => F
				    [female] => T
				    [mon_open1] => 
				    [mon_close1] => 
				    [mon_open2] => 
				    [mon_close2] => 
				    [tue_open1] => 
				    [tue_close1] => 
				    [tue_open2] => 
				    [tue_close2] => 
				    [wed_open1] => 
				    [wed_close1] => 
				    [wed_open2] => 
				    [wed_close2] => 
				    [thu_open1] => 
				    [thu_close1] => 
				    [thu_open2] => 
				    [thu_close2] => 
				    [fri_open1] => 
				    [fri_close1] => 
				    [fri_open2] => 
				    [fri_close2] => 
				    [sat_open1] => 
				    [sat_close1] => 
				    [sat_open2] => 
				    [sat_close2] => 
				    [sun_open1] => 
				    [sun_close1] => 
				    [sun_open2] => 
				    [sun_close2] => 
				    [nationalproviderid] => 1912007634
				    [net_start_date] => 2016/01/01
				    [dentemax] => F
				    [lucent] =>  
				    [offcode] => F
				    [anctest] => F
				    [hospcode] => 
				    [hosp] => 
				    [rpq] =>  
				    [horizon_dental_epo] =>  
				    [board_certified] => F
				    [specialty] => 
				    [latitude] => 39.92017
				    [longitude] => -75.00353
				    [ehr] =>  
				    [qrp] =>  
				    [ncqa] =>  
				    [hnjh_ind] => F
				    [practice_limitation] => 
				    [acpt_new_pat] => 
				    [mcr_rating] => 
				    [mbpc] => T
				    [brnb] => F
				    [shmc] => F
				    [ccx] => 
				    [omt1] => T
				    [omt2] => F
				    [oat1] => F
				    [ost2] => F
				    [ost1] => F
				    [oat2] => F
				    [omt1_eff_dt] => 2016/01/01
				    [omt1_end_dt] => 4000/01/01
				    [omt2_eff_dt] => 
				    [omt2_end_dt] => 
				    [oat1_eff_dt] => 
				    [oat1_end_dt] => 
				    [ost2_eff_dt] => 
				    [ost2_end_dt] => 
				    [ost1_eff_dt] => 
				    [ost1_end_dt] => 
				    [oat2_eff_dt] => 
				    [oat2_end_dt] => 
				    [trad_eff_dt] => 2001/01/02
				    [trad_end_dt] => 4000/01/01
				    [mgcn_eff_dt] => 2001/01/02
				    [mgcn_end_dt] => 4000/01/01
				    [mcbl_eff_dt] => 2010/08/16
				    [mcbl_end_dt] => 4000/01/01
				    [advn_eff_dt] => 2013/10/01
				    [advn_end_dt] => 4000/01/01
				    [aco_eff_dt] => 
				    [aco_end_dt] => 
				    [pcmh_eff_dt] => 
				    [pcmh_end_dt] => 
				    [mbpc_eff_dt] => 2014/11/01
				    [mbpc_end_dt] => 4000/01/01
				    [brnb_eff_dt] => 
				    [brnb_end_dt] => 
				    [shmc_eff_dt] => 
				    [shmc_end_dt] => 
				)

		*/

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
