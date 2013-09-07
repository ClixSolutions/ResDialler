<?php
####################################################################################################
####  Name:             	go_site.php                                                    	    ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:       	Rodolfo Januarius T. Manipol                                        ####
####  Edited by:            Jerico James F. Milo                                                ####
####                        Christopher P. Lomuntad                                             ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_site extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->library('session');
		//$this->load->library('curl');
		$this->load->library('commonhelper');
		$this->load->helper('date','file','form','download');
		$this->is_logged_in();
		$this->clear_cache();
		$this->load->library('email');
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);
		$this->load->library('encrypt');
		$this->lang->load('userauth', $this->session->userdata('ua_language'));
	}

	function go_dashboard()
	{
		$data['go_main_content'] = 'go_dashboard';
		$data['cssloader'] = 'go_dashboard_cssloader.php';
		$data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
		$data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_userfulname();
		$data['bannertitle'] = 'Dashboard';
		$data['userfulname'] = $callfunc;
		$callfunc = $this->go_dashboard->go_get_userinfo();
		$data['uname'] = $callfunc->user;
		$data['upass'] = $callfunc->pass;
		$data['phone_login'] = $callfunc->phone_login;
		$data['phone_pass'] = $callfunc->phone_pass;
		$data['is_active'] = $callfunc->active;
		$data['das']= 'wp-has-current-submenu';
		//$callfunc = $this->go_dashboard->go_check_if_new();
		//$data['chkifnew'] = $callfunc;

		$data['theme'] = $this->session->userdata('go_theme');
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
        	$data['account'] = $this->session->userdata('user_name');
		$data['campperms'] = $this->commonhelper->getPermissions("campaign",$this->session->userdata("user_group"));

		
		#### REALTIME MONITORING ####
		$this->load->model('go_monitoring');
		$sCampaign = $this->uri->segment(6);
	    $campaign_ids = $this->go_monitoring->go_get_campaigns();
		$data['campaign_ids'] = $campaign_ids['list'];
		$data['selected_campaign'] = $sCampaign;

		$this->load->view('includes/go_dashboard_template', $data);
	}
	
	function sippyinfo() {
		#### BALANCE via SIPPY
		$this->load->model('go_dashboard');
		$sippyinfo = $this->go_dashboard->go_sippy_info();
		$carrierinfo = $this->go_dashboard->go_carrier_info();
		$count_sippyinfo = count($sippyinfo);		
		
		if($count_sippyinfo > 0) {

			foreach($sippyinfo as $sippyitem) {
				$web_username = $sippyitem->username;
				$web_password = $sippyitem->web_password;
				$web_iaccount = $sippyitem->i_account;
				$voip_authname = $sippyitem->authname;
				$voip_password = $sippyitem->voip_password;
			}

			foreach($carrierinfo as $carrieritem) {
				$idcarrier = $carrieritem->carrier_id;
				$namecarrier = $carrieritem->carrier_name;
				$activecarrier = $carrieritem->active;
			}
		
			$data['web_username'] = $web_username;	
			$data['web_password'] = $web_password;	
			$data['voip_authname'] = $voip_authname;	
			$data['voip_password'] = $voip_password;	
			$data['count_sippyinfo'] = $count_sippyinfo;
			$data['idcarrier'] = $idcarrier;
			$data['namecarrier'] = $namecarrier;
			$data['activecarrier'] = $activecarrier;
			
			$struct = $this->commonhelper->getAccountInfo("username",$web_username);
                        
			$accval = $struct->structmem('username');
                        $acc = $accval->getval();
                        $data['acc'] = $acc;

                        $companyval = $struct->structmem('company_name');
                        $sippycompanies = $companyval->getval();
                        $data['sippy_companies'] = $sippycompanies;

                        $fnameval = $struct->structmem('first_name');
                        $sippyfirstname = $fnameval->getval();
                        $data['sippy_firstname'] = $sippyfirstname;

                        $lnameval = $struct->structmem('last_name');
                        $sippylastname = $lnameval->getval();
                        $data['sippy_lastname'] = $sippylastname;

                        $phoneval = $struct->structmem('phone');
                        $sippyphone = $phoneval->getval();
                        $data['sippy_phone'] = $sippyphone;

                        $faxval = $struct->structmem('fax');
                        $sippyfax = $faxval->getval();
                        $data['sippy_fax'] = $sippyfax;

                        $postalval = $struct->structmem('postal_code');
                        $sippypostalcode = $postalval->getval();
                        $data['sippy_postal_code'] = $sippypostalcode;

                        $addval = $struct->structmem('street_addr');
                        $sippyaddress = $addval->getval();
                        $data['sippy_address'] = $sippyaddress;

                        $cityval = $struct->structmem('city');
                        $sippycity = $cityval->getval();
                        $data['sippy_city'] = $sippycity;

                        $stateval = $struct->structmem('state');
                        $sippystate = $stateval->getval();
                        $data['sippy_state'] = $sippystate;

                        $countryval = $struct->structmem('country');
                        $sippycountry = $countryval->getval();
                        $data['sippy_country'] = $sippycountry;

			$emailval = $struct->structmem('email');
                        $sippyemail = $emailval->getval();
                        $data['sippy_email'] = $sippyemail;
	
			$sumval = $struct->structmem('balance');
                        $totalbalance = $sumval->getval();
			
			$negative = "";
			if ($totalbalance < 0) {
                         	$totalbalance = abs($totalbalance);
                        } else {
                         	$negative = "-";
                         	$data['negative'] = $negative;
                        }
	
			$data['totalbalance'] = $totalbalance;

			
	
		}#count sippyinfo		
		
		$this->load->view('go_dashboard_sippy', $data);

		#### END BALANCE via SIPPY
	
	
	}

	function go_administrator()
	{
		$data['go_main_content'] = 'go_administrator';


		$data['cssloader'] = 'go_admin_cssloader.php';
		$data['jsheaderloader'] = 'go_admin_header_jsloader.php';
		$data['jsbodyloader'] = 'go_admin_body_jsloader.php';



		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = 'Dialer > Users';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = '';
		$data['foldlink'] = '<li class="wp-menu-separator"><a class="separator" href="../go_site/fold_me"><br></a></li>';
		$this->load->view('includes/go_admin_template', $data);
	}

	function go_reports()
	{
		$this->session->unset_userdata('pagetitle');
		$data['go_main_content'] = 'go_reports/go_reports';
		$data['cssloader'] = 'go_dashboard_cssloader.php';
		$data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
		$data['jsbodyloader'] = 'go_reports_body_jsloader.php';

		$this->load->model(array('go_reports','go_dashboard'));
		$callfunc = $this->go_reports->go_get_userfulname();
	    $campaign_ids = $this->go_reports->go_get_campaigns();
		$inbound_groups = $this->go_reports->go_get_inbound_groups();
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);
	    $data['campaign_ids'] = $campaign_ids;
		$data['inbound_groups'] = $inbound_groups;
		$data['userfulname'] = $callfunc;
		$data['uname'] = $callfunc;
		$data['gouser'] = $this->session->userdata('user_name');
		$data['gopass'] = $this->session->userdata('user_pass');
	    $data['groupId'] = $this->go_reports->go_get_groupid();

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = 'Reports & Analytics';
		$data['rep']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';


		$this->load->view('includes/go_reports_template', $data);
	}
	
	

	function go_reports_page()
	{
		$data['pagetitle'] = $this->uri->segment(3);
//		$data['pagetitle'] = $this->session->userdata('pagetitle');
		$this->load->model('go_reports');
//		$callfunc = $this->go_reports->go_get_campaigns();
//		$data['credit'] = $callfunc['credit'];
//		$data['currency'] = $callfunc['currency'];
		$data['campaign_id'] = $this->go_reports->go_get_campaigns();
		if ($data['pagetitle'] == '') {
			$data['pagetitle'] = 'stats';
		}

		if (!isset($request) || $request=='') {
			if ($data['pagetitle']=='stats') {
				$data['request'] = 'daily';
			} else {
				$data['request'] = 'outbound';
			}
		} else {
			$data['request'] = $request;
		}

		$this->load->view('go_reports/go_reports_output', $data);
	}

	function go_reports_output()
	{

             $this->load->helper(array('html','form'));


		$data['pagetitle'] = $this->uri->segment(3);
		$data['from_date'] = $this->uri->segment(4);
		$data['to_date'] = $this->uri->segment(5);
		$data['campaign_id'] = $this->uri->segment(6);
		$data['request'] = $this->uri->segment(7);
		$this->load->model(array('go_reports','go_script'));
	    $groupId = $this->go_reports->go_get_groupid();

		if ($data['pagetitle'] == '') {
			$data['pagetitle'] = 'stats';
		}

		if ($data['campaign_id']!='' && $data['campaign_id']!='null')
		{
			$callfunc = $this->go_reports->go_get_reports();
			$groupId = $callfunc['groupId'];
		}
		
		if ($data['pagetitle'] == 'call_export_report')
			$callfunc = $this->go_reports->go_get_reports();

		$data['request'] 			= $callfunc['request'];
		$data['groupId'] 			= $groupId;
		$data['data_calls'] 		= $callfunc['data_calls'];
		$data['total_calls'] 		= $callfunc['total_calls'];
		$data['total_agents'] 		= $callfunc['total_agents'];
		$data['total_leads'] 		= $callfunc['total_leads'];
		$data['total_status'] 		= $callfunc['total_status'];
		$data['total_new'] 			= $callfunc['total_new'];
		$data['data_status'] 		= $callfunc['data_status'];
		$data['data_agents'] 		= $callfunc['data_agents'];
		$data['campaign_name'] 		= $callfunc['campaign_name'];
		$data['statuses']			= $callfunc['statuses'];
		$data['TOPsorted_output']		= $callfunc['TOPsorted_output'];
		$data['BOTsorted_output']		= $callfunc['BOTsorted_output'];
		$data['TOPsorted_outputFILE']	= $callfunc['TOPsorted_outputFILE'];
		$data['TOTwait']				= $callfunc['TOTwait'];
		$data['TOTtalk']				= $callfunc['TOTtalk'];
		$data['TOTdispo']				= $callfunc['TOTdispo'];
		$data['TOTpause']				= $callfunc['TOTpause'];
		$data['TOTdead']				= $callfunc['TOTdead'];
		$data['TOTcustomer']			= $callfunc['TOTcustomer'];
		$data['TOTALtime']			= $callfunc['TOTALtime'];
		$data['TOTtimeTC']			= $callfunc['TOTtimeTC'];
		$data['sub_statusesTOP']	= $callfunc['sub_statusesTOP'];
		$data['SUMstatuses']		= $callfunc['SUMstatuses'];
		$data['TOT_AGENTS']			= $callfunc['TOT_AGENTS'];
		$data['TOTcalls']			= $callfunc['TOTcalls'];
		$data['TOTtime_MS']			= $callfunc['TOTtime_MS'];
		$data['TOTtotTALK_MS']		= $callfunc['TOTtotTALK_MS'];
		$data['TOTtotDISPO_MS']		= $callfunc['TOTtotDISPO_MS'];
		$data['TOTtotDEAD_MS']		= $callfunc['TOTtotDEAD_MS'];
		$data['TOTtotPAUSE_MS']		= $callfunc['TOTtotPAUSE_MS'];
		$data['TOTtotWAIT_MS']		= $callfunc['TOTtotWAIT_MS'];
		$data['TOTtotCUSTOMER_MS']	= $callfunc['TOTtotCUSTOMER_MS'];
		$data['TOTavgTALK_MS']		= $callfunc['TOTavgTALK_MS'];
		$data['TOTavgDISPO_MS']		= $callfunc['TOTavgDISPO_MS'];
		$data['TOTavgDEAD_MS']		= $callfunc['TOTavgDEAD_MS'];
		$data['TOTavgPAUSE_MS']		= $callfunc['TOTavgPAUSE_MS'];
		$data['TOTavgWAIT_MS']		= $callfunc['TOTavgWAIT_MS'];
		$data['TOTavgCUSTOMER_MS']	= $callfunc['TOTavgCUSTOMER_MS'];
		$data['TOTtotTOTAL_MS']		= $callfunc['TOTtotTOTAL_MS'];
		$data['TOTtotNONPAUSE_MS']	= $callfunc['TOTtotNONPAUSE_MS'];
		$data['TOTtotPAUSEB_MS']	= $callfunc['TOTtotPAUSEB_MS'];
		$data['MIDsorted_output']	= $callfunc['MIDsorted_output'];
		$data['SstatusesTOP']		= $callfunc['SstatusesTOP'];
		$data['SstatusesSUM']		= $callfunc['SstatusesSUM'];
		$data['SstatusesBOT']		= $callfunc['SstatusesBOT'];
		$data['SstatusesBOTR']		= $callfunc['SstatusesBOTR'];
		$data['SstatusesBSUM']		= $callfunc['SstatusesBSUM'];
		$data['TOToutbound']		= $callfunc['TOToutbound'];
		$data['TOTinbound']			= $callfunc['TOTinbound'];
		$data['allowed_campaigns']	= $callfunc['allowed_campaigns'];
		$data['inbound_groups']		= $callfunc['inbound_groups'];
		$data['lists_to_print']		= $callfunc['lists_to_print'];
		$data['statuses_to_print']	= $callfunc['statuses_to_print'];
		$data['custom_fields_enabled']=$callfunc['custom_fields_enabled'];
		$data['total_calls']		= $callfunc['total_calls'];
		$data['total_contacts']		= $callfunc['total_contacts'];
		$data['total_noncontacts']	= $callfunc['total_noncontacts'];
		$data['total_sales']		= $callfunc['total_sales'];
		$data['total_xfer']			= $callfunc['total_xfer'];
		$data['total_notinterested']= $callfunc['total_notinterested'];
		$data['total_callbacks']	= $callfunc['total_callbacks'];
		$data['total_talk_hours']	= $callfunc['total_talk_hours'];
		$data['total_pause_hours']	= $callfunc['total_pause_hours'];
		$data['total_wait_hours']	= $callfunc['total_wait_hours'];
		$data['total_dispo_hours']	= $callfunc['total_dispo_hours'];
		$data['total_dead_hours']	= $callfunc['total_dead_hours'];
		$data['total_login_hours']	= $callfunc['total_login_hours'];
		$data['total_dialer_calls_output']= $callfunc['total_dialer_calls_output'];
		$data['total_dialer_calls']	= $callfunc['total_dialer_calls'];
		$data['file_output']		= $callfunc['file_output'];
		$data['call_time']			= $callfunc['call_time'];

                $data['survey'] = $this->go_script->get_surveys();


		$this->load->view('go_reports/go_reports_page', $data);
	}


	function go_export_reports()
	{
		$data['pagetitle'] = $this->uri->segment(3);
		$data['from_date'] = $this->uri->segment(4);
		$data['to_date'] = $this->uri->segment(5);
		$data['campaign_id'] = $this->uri->segment(6);
		$data['request'] = $this->uri->segment(7);
		$this->load->model('go_reports');

		if ($data['campaign_id']!='')
		{
			$callfunc = $this->go_reports->go_get_reports();
		}

		$data['file_output']	= $callfunc['file_output'];

		$this->load->view('go_reports/go_export_reports', $data);
	}


        function display_surveys(){
             $this->load->model('go_script');
             if($_POST['surveyid'] != 0){

                  $daterange = explode(" to ",$_POST['daterange']);
                  $survey = $this->go_script->get_limesurveys($_POST['surveyid'],$daterange);   
                  echo json_encode($survey);

             }
        }


	function go_dashboard_sales_today()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_outbound_today();
		$data['outbound_today'] = $callfunc;
		$callfunc = $this->go_dashboard->go_inbound_today();
		$data['inbound_today'] = $callfunc;
		$callfunc = $this->go_dashboard->go_inbound_sph();
		$data['inbound_sph'] = $callfunc;
		$callfunc = $this->go_dashboard->go_outbound_sph();
		$data['outbound_sph'] = $callfunc;
		$this->load->view('go_dashboard_sales', $data);
	}

	function go_dashboard_calls_today()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_live_inbound_today();
		$data['live_inbound_today'] = $callfunc;
		$callfunc = $this->go_dashboard->go_live_outbound_today();
		$data['live_outbound_today'] = $callfunc;
		$callfunc = $this->go_dashboard->go_calls_outbound_queue_today();
		$data['calls_outbound_queue_today'] = $callfunc;
		$callfunc = $this->go_dashboard->go_calls_inbound_queue_today();
		$data['calls_inbound_queue_today'] = $callfunc;
		$callfunc = $this->go_dashboard->go_calls_ringing_today();
		$data['calls_ringing_today'] = $callfunc;
		$callfunc = $this->go_dashboard->go_ibcalls_morethan_minute();
		$data['ibcalls_morethan_minute'] = $callfunc;
		$callfunc = $this->go_dashboard->go_obcalls_morethan_minute();
		$data['obcalls_morethan_minute'] = $callfunc;
		$callfunc = $this->go_dashboard->go_total_calls();
		$data['total_calls'] = $callfunc;
		$this->load->view('go_dashboard_calls', $data);
	}

	function go_dashboard_drops_today()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_dropped_calls_today();
		$data['dropped_calls_today'] = $callfunc->drops_today;
		$data['answered_calls_today'] = $callfunc->answers_today;
		$callfunc = $this->go_dashboard->go_total_calls();
		$data['total_calls'] = $callfunc;
		$this->load->view('go_dashboard_drop_percentage', $data);
	}

	function go_dashboard_agents()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_total_agents_call();
		$data['total_agents_call'] = $callfunc;
		$callfunc = $this->go_dashboard->go_total_agents_paused();
		$data['total_agents_paused'] = $callfunc;
		$callfunc = $this->go_dashboard->go_total_agents_wait_calls();
		$data['total_agents_wait_calls'] = $callfunc;
		$callfunc = $this->go_dashboard->go_total_agents_online();
		$data['total_agents_online'] = $callfunc;
		$this->load->view('go_dashboard_agents', $data);
	}


	function go_dashboard_analytics_in()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');

                $callfunc  = $this->go_dashboard->go_get_hourly_in_sales();
		$querycount = $callfunc['datacount'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		if ($querycount==0){
			$callfunc = $this->go_dashboard->go_get_weekly_in_sales();
			$querycount = $callfunc['datacount'];

			$queryval = $callfunc['dataval'];

			foreach($queryval as $item):
			$cnt = $item->weekno;
			endforeach;

			if ($cnt==null){
				$querycount = 0;
			}

			if ($querycount==0){
				$callfunc = $this->go_dashboard->go_get_cmonth_daily_in_sales();
				$queryval = $callfunc['dataval'];
				$data['get_cmonth_daily_in_sales'] = $queryval;
				$data['request'] = 'monthly';
				$this->load->view('go_dashboard_analytics_in_cmonth_daily', $data);


			}
			else{
				$queryval = $callfunc['dataval'];
				$data['get_weekly_in_sales'] = $queryval;
				$data['request'] = 'weekly';
				$this->load->view('go_dashboard_analytics_in_weekly', $data);
			}


		}
		else
		{
		$queryval = $callfunc['dataval'];
		$data['get_hourly_in_sales'] = $queryval;
		$data['request'] = 'hourly';
		$this->load->view('go_dashboard_analytics_in_hourly', $data);
		}
	}

	function go_dashboard_analytics_in_hourly()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_hourly_in_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_hourly_in_sales'] = $queryval;
		$data['request'] = 'hourly';
		$this->load->view('go_dashboard_analytics_in_hourly', $data);
	}


	function go_dashboard_analytics_in_weekly()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_weekly_in_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_weekly_in_sales'] = $queryval;
		$data['request'] = 'weekly';
		$this->load->view('go_dashboard_analytics_in_weekly', $data);
	}

	function go_dashboard_analytics_in_cmonth_daily()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_cmonth_daily_in_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_cmonth_daily_in_sales'] = $queryval;
		$data['request'] = 'monthly';
		$this->load->view('go_dashboard_analytics_in_cmonth_daily', $data);
	}

	function go_dashboard_analytics_in_popup_cmonth_daily()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_cmonth_daily_in_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_cmonth_daily_in_sales'] = $queryval;
		$data['request'] = 'monthly';
		$this->load->view('go_dashboard_analytics_in_popup_cmonth_daily', $data);
	}

	function go_dashboard_analytics_in_popup_weekly()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_weekly_in_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_weekly_in_sales'] = $queryval;
		$data['request'] = 'weekly';
		$this->load->view('go_dashboard_analytics_in_popup_weekly', $data);
	}

	function go_dashboard_analytics_in_popup_hourly()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_hourly_in_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_hourly_in_sales'] = $queryval;
		$data['request'] = 'hourly';
		$this->load->view('go_dashboard_analytics_in_popup_hourly', $data);
	}

	function go_dashboard_analytics_out()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');

                $callfunc  = $this->go_dashboard->go_get_hourly_out_sales();
		$querycount = $callfunc['datacount'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		if ($querycount==0){
			$callfunc = $this->go_dashboard->go_get_weekly_out_sales();
			$querycount = $callfunc['datacount'];

			$queryval = $callfunc['dataval'];

			foreach($queryval as $item):
			$cnt = $item->weekno;
			endforeach;

			if ($cnt==null){
				$querycount = 0;
			}

			if ($querycount==0){
				$callfunc = $this->go_dashboard->go_get_monthly_out_sales();
				$queryval = $callfunc['dataval'];
				$data['get_monthly_out_sales'] = $queryval;
				$data['request'] = 'monthly';
				$this->load->view('go_dashboard_analytics_out_monthly', $data);


			}
			else{
				$queryval = $callfunc['dataval'];
				$data['get_weekly_out_sales'] = $queryval;
				$data['request'] = 'weekly';
				$this->load->view('go_dashboard_analytics_out_weekly', $data);
			}


		}
		else
		{
		$queryval = $callfunc['dataval'];
		$data['get_hourly_out_sales'] = $queryval;
		$data['request'] = 'hourly';
		$this->load->view('go_dashboard_analytics_out_hourly', $data);
		}
	}

	function go_dashboard_analytics_out_hourly()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_hourly_out_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_hourly_out_sales'] = $queryval;
		$data['request'] = 'hourly';
		$this->load->view('go_dashboard_analytics_out_hourly', $data);
	}


	function go_dashboard_analytics_out_weekly()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_weekly_out_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_weekly_out_sales'] = $queryval;
		$data['request'] = 'weekly';
		$this->load->view('go_dashboard_analytics_out_weekly', $data);
	}

	function go_dashboard_analytics_out_monthly()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_get_monthly_out_sales();
		$queryval = $callfunc['dataval'];

                $groupIdfunc  = $this->go_dashboard->go_get_groupId();
		$data['groupId'] = $groupIdfunc;

		$data['get_monthly_out_sales'] = $queryval;
		$data['request'] = 'monthly';
		$this->load->view('go_dashboard_analytics_out_monthly', $data);
	}





	function go_dashboard_leads()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');
		$callfunc = $this->go_dashboard->go_total_hopper_leads();
		$data['total_hopper_leads'] = $callfunc;
		$callfunc = $this->go_dashboard->go_total_dialable_leads();
		$data['total_dialable_leads'] = $callfunc;
		$callfunc = $this->go_dashboard->go_total_leads();
		$data['total_leads'] = $callfunc;

		//$callfunc = $this->go_dashboard->go_hopper_leads_warning();
		//$data['hopper_leads_warning_querycount'] = $callfunc['datacount'];
		//$data['hopper_leads_warning_queryval'] = $callfunc['dataval'];

		//$callfunc = $this->go_dashboard->go_hopper_leads_warning_zero();
		//$data['hopper_leads_warning_zero_querycount'] = $callfunc['datacount'];
		//$data['hopper_leads_warning_zero_queryval'] = $callfunc['dataval'];

		//$data['tot_hopper_leads_warning'] = $data['hopper_leads_warning_querycount'] + $data['hopper_leads_warning_zero_querycount'];


		$callfunc = $this->go_dashboard->go_hopper_leads_warning_less_h();
		$data['go_hopper_leads_warning_less_h_querycount'] = $callfunc['datacount'];
		$data['go_hopper_leads_warning_less_h_queryval'] = $callfunc['dataval'];



		$this->load->view('go_dashboard_leads', $data);
	}



	function go_dashboard_search()
	{

	    	$queryString = $this->uri->segment(3);

		if ($queryString != ''){


		#DATABASE REPORTS
		$this->load->model('go_dashboard');

		#SEARCH IN USERS TABLE
		$callfunc = $this->go_dashboard->go_search_user();
		$data['go_user_datacount'] 	= $callfunc['datacount'];
		$data['go_user_dataval'] 	= $callfunc['dataval'];

		#SEARCH IN LIST - NAME TABLE
		$callfunc = $this->go_dashboard->go_search_list();
		$data['go_liname_datacount'] 	= $callfunc['datacount'];
		$data['go_liname_dataval'] 	= $callfunc['dataval'];

		#SEARCH IN LIST - PHONE TABLE
		$callfunc = $this->go_dashboard->go_search_phone();
		$data['go_liphone_datacount'] 	= $callfunc['datacount'];
		$data['go_liphone_dataval'] 	= $callfunc['dataval'];

		$this->load->view('go_dashboard_search', $data);

		}

		else
		{
		$callfunc = $this->go_dashboard->go_search_clear();
		}

	}



	function go_rssview()
	{
		$this->load->library('simplepie');
		$this->simplepie = new SimplePie();
		$this->simplepie->set_feed_url('http://goautodial.org/projects/goautodialce/news.atom');
		#$this->simplepie->set_cache_location(base_url() . 'cache/rss');
		$this->simplepie->init();
		$this->simplepie->handle_content_type();
		$data['res_feed'] = $this->simplepie->get_items();
		$this->load->view('go_rssviewer', $data);
	}

	function go_rssview_others()
	{
		$this->load->library('simplepie');
		$this->simplepie = new SimplePie();
		$this->simplepie->set_feed_url('http://goautodial.org/projects/goautodialce/boards/3.atom?key=980b271e234023ad6b9509b3fc6a3438b702a7b6');
		#$this->simplepie->set_cache_location(base_url() . 'cache/rss');
		$this->simplepie->init();
		$this->simplepie->handle_content_type();
		$data['res_feed'] = $this->simplepie->get_items();
		$this->load->view('go_rssviewer_others', $data);
	}

	function go_server_vitals()
	{
		$this->load->view('go_server_vitals');
	}

	function go_server_hardware()
	{
		$this->load->view('go_server_hardware');
	}

	function go_server_memory()
	{
		$this->load->view('go_server_memory');
	}

	function go_server_filesystems()
	{
		$this->load->view('go_server_filesystems');
	}

	function go_phpmyadmin()
	{
		$this->load->view('phpmyadmin/index');
	}

	function set_language()
	{
		$language = $this->uri->segment(3);
		if ( empty($language) ) { $language = $this->input->post('lang_select'); }
		if ( $language == 'detect' ) {
			// kill the cookie & session variable
			set_cookie('ua_language', '');
			$this->session->set_userdata('ua_language', '');
		} else {
			// switch session's language and set a cookie
			set_cookie('ua_language', $language,
				$this->config->item('remember_me_life'));
			$this->session->set_userdata('ua_language', $language);
		}
		redirect ($this->session->flashdata('uri'));
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		$base = base_url();
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
			//echo "<script>javascript: window.location = '$base'</script>";
			#echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
			die();
			#$this->load->view('go_login_form');
		}
	}

    function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }


	function fold_me()
	{
		$data['bannertitle'] = $this->session->userdata('bannertitle');
		$data['folded'] = 'folded';
		$data['foldlink'] = '../go_site/unfold_me';
		$data['go_main_content'] = 'go_dashboard';
		$this->load->view('includes/go_dashboard_template', $data);
	}


	function unfold_me()
	{
		$data['bannertitle'] = $this->session->userdata('bannertitle');
		$data['folded'] = '';
		$data['foldlink'] = '../go_site/fold_me';
		$data['go_main_content'] = 'go_dashboard';
		$this->load->view('includes/go_dashboard_template', $data);
	}


	function logout()
	{
		$this->commonhelper->auditadmin('LOGOUT');
		#$remember_me = $this->session->userdata('remember_me');
		#if ($remember_me=='') {
			$this->load->model('remember_me');
			$this->remember_me->removeRememberMe($this->input->post('user_name'));
		#	}
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		#$this->session->set_userdata(array('user_name'=>''));
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_pass');
		$this->session->unset_userdata('bannertitle');
		$this->session->unset_userdata('ua_language');
		$this->session->set_userdata('is_logged_in',FALSE);
		#$this->session->sess_unset();
		$this->session->sess_destroy();
		#$this->index();
		#header("index()");
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!');</script>";
        $this->session->set_userdata(array('user_name' => '', 'is_logged_in' => ''));
		redirect(base_url(),'location');
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!'); window.location='../../'; history.replaceState ='../../'</script>";
	}

	function closebrowser()
	{
		$remember_me = $this->session->userdata('remember_me');
		if ($remember_me=='') {
			$this->load->model('remember_me');
			$this->remember_me->removeRememberMe($this->input->post('user_name'));
			}
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		#$this->session->set_userdata(array('user_name'=>''));
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_pass');
		$this->session->unset_userdata('bannertitle');
		$this->session->unset_userdata('ua_language');
		$this->session->set_userdata('is_logged_in',FALSE);
		#$this->session->sess_unset();
		$this->session->sess_destroy();
		#$this->index();
		#header("index()");
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!');</script>";
		redirect('go_site/go_dashboard','location');
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!'); window.location='../../'; history.replaceState ='../../'</script>";
	}

	function go_monitoring()
	{
	    $this->load->model('go_monitoring');
	    $type = $this->uri->segment(5);
	    $realtime = $this->go_monitoring->go_update_realtime();
	    $data['realtimeHTML'] =		$realtime['html'];
	    $data['no_live_calls'] =	$realtime['no_live_calls'];
	    $data['no_agents_logged'] =	$realtime['no_agents_logged'];
	    $data['active_calls'] =		$realtime['active_calls'];
	    $data['agent_total'] =		$realtime['agent_total'];
	    $data['agent_incall'] =		$realtime['agent_incall'];
	    $data['agent_paused'] =		$realtime['agent_paused'];
	    $data['agent_ready'] =		$realtime['agent_ready'];
	    $data['agent_waiting'] =	$realtime['agent_waiting'];
	    $data['user_level'] =		$this->userLevel;
		if ($type=='agents')
		{
		    $this->load->view('go_monitoring/go_realtime',$data);
		} else {
			$this->load->view('go_monitoring/go_call_realtime',$data);
		}
	}

	function go_dropped_calls()
	{
		$this->load->model('go_monitoring');
		$data['dropped_calls_table'] = $this->go_monitoring->go_dropped_call_list();
		$this->load->view('go_monitoring/go_dropped_calls',$data);
	}
	
    function widgetconfigured(){
		$this->load->library('commonhelper');
		$this->load->model('go_dashboard');
		$this->go_dashboard->goautodial->where(array('user_id'=>$_POST['user_id']));
		$configs = $this->go_dashboard->goautodial->get('go_widget_position');
		if($configs->num_rows > 0){
                        $permissions = $this->commonhelper->getPermissions("dashboard",$this->session->userdata("user_group"));

			foreach($configs->result() as $config){
				$configured['left_html'] = explode(",",$config->left_html);
				$configured['right_html'] = explode(",",$config->right_html);
				$configured['dashboard_todays_status'] = $permissions->dashboard_todays_status === "N"? "PN" : $config->today_status;
				$configured['dashboard_agents_status'] = $permissions->dashboard_agent_lead_status === "N"? "PN" : $config->agent_status;
				$configured['account_info_status'] = $permissions->dashboard_account_info === "N" ? "PN" : $config->account_info;
				$configured['dashboard_accounts'] = $permissions->dashboard_account_info === "N" ? "PN" :$config->account_info;
				$configured['dashboard_analytics'] = $permissions->dashboard_go_analytics === "N"? "PN" : $config->go_analytics;
				$configured['agents_phones_logins'] = $config->agents_and_phones;
			        $configured["dashboard_goautodial_forum"] = $config->dashboard_goautodial_forum;
			        $configured["dashboard_goautodial_news"] = $config->dashboard_goautodial_news;
			        $configured["dashboard_controls"] = $permissions->dashboard_system_service === "N" ? "PN" : $config->dashboard_controls;
			        $configured["dashboard_server_statistics"] = $permissions->dashboard_server_settings === "N" ? "PN" : $config->server_statistics;
			}
			echo json_encode($configured);
		}
	}

	function widgetconfig(){
		$this->load->library('commonhelper');
		$this->load->model('go_dashboard');
		$this->go_dashboard->goautodial->where(array('user_id'=>$_POST['user_id']));
		$configs = $this->go_dashboard->goautodial->get('go_widget_position');
               
		if($configs->num_rows == 0){
			$dataconfig = array();
			if(!empty($_POST)){
				foreach($_POST as $col => $val){
					if(preg_match("/todays_status/",$col)){
						$dataconfig["today_status"] = $val;
					}elseif(preg_match("/dashboard_accounts/",$col)){
						$dataconfig["account_info"] = $val;
					}elseif(preg_match("/dashboard_analytics/",$col)){
						$dataconfig["go_analytics"] = $val;
					}elseif(preg_match("/agents_status/",$col)){
						$dataconfig["agent_status"] = $val;
					}elseif(preg_match("/agents_phones/",$col)){
						$dataconfig["agents_and_phones"] = $val;
					}elseif(preg_match("/dashboard_server_statistics/",$col)){
						$dataconfig["server_statistics"] = $val;
					}elseif(preg_match("/dashboard_plugins/",$col)){
						$dataconfig["plugins"] = $val;
					}elseif(preg_match("/dashboard_goautodial_news/",$col)){
						$dataconfig["dahsboard_goautodial_news"] = $val;
					}elseif(preg_match("/dashboard_goautodial_forum/",$col)){
						$dataconfig["dashboard_goautodial_forum"] = $val;
					}elseif(preg_match("/dashboard_controls/",$col)){
						$dataconfig["dashboard_controls"] = $val;
					}else{
						$dataconfig[$col] = $val;
					}
				}
			}
			$this->go_dashboard->goautodial->trans_start();
			   $this->go_dashboard->goautodial->insert('go_widget_position',$dataconfig);
			$this->go_dashboard->goautodial->trans_complete();

		} else {

			$dataconfig = array();
			foreach($_POST as $col => $val){
					if(preg_match("/todays_status/",$col)){
						$dataconfig["today_status"] = $val;
					}elseif(preg_match("/dashboard_accounts/",$col)){
						$dataconfig["account_info"] = $val;
					}elseif(preg_match("/dashboard_analytics/",$col)){
						$dataconfig["go_analytics"] = $val;
					}elseif(preg_match("/agents_status/",$col)){
						$dataconfig["agent_status"] = $val;
					}elseif(preg_match("/agents_phones/",$col)){
						$dataconfig["agents_and_phones"] = $val;
					}elseif(preg_match("/dashboard_server_statistics/",$col)){
						$dataconfig["server_statistics"] = $val;
					}elseif(preg_match("/dashboard_plugins/",$col)){
						$dataconfig["plugins"] = $val;
					}elseif(preg_match("/dashboard_goautodial_news/",$col)){
						$dataconfig["dashboard_goautodial_news"] = $val;
					}elseif(preg_match("/dashboard_goautodial_forum/",$col)){
						$dataconfig["dashboard_goautodial_forum"] = $val;
					}elseif(preg_match("/dashboard_controls/",$col)){
						$dataconfig["dashboard_controls"] = $val;
					}else{
						$dataconfig[$col] = $val;
					}
			}

			$this->go_dashboard->goautodial->trans_start();
			$this->go_dashboard->goautodial->where("user_id",$_POST['user_id']);
			$this->go_dashboard->goautodial->update('go_widget_position',$dataconfig);
			$this->go_dashboard->goautodial->trans_complete();

			$_POST["left_html"] = explode(",",$_POST["left_html"]);
			$_POST["right_html"] = explode(",",$_POST["right_html"]);
			echo json_encode($_POST);
//var_dump($_POST);
		}

	}


	function get_notification()
	{
		$this->load->model('go_dashboard');
		//$groupId  = $this->go_dashboard->go_get_groupid();
        $user = $this->session->userdata('user_name');
		$return = "";

	    $this->godb = $this->load->database('goautodialdb', true);

		$query = $this->godb->query("SELECT id,user,notification,title,message,type FROM go_notifications WHERE user='$user' AND status='UNCLICKED'");
		$result = $query->result();

		$num = 0;
		foreach ($result as $line)
		{
			foreach ($line as $row)
			{
				$return[$num] .= "$row|";
			}
			$return[$num] = rtrim($return[$num],'|');
			$output .= "$return[$num]!N!";
			$num++;
		}

		echo rtrim($output,'!N!');

// 		$query = $this->godb->query("UPDATE go_notifications SET status='CLICKED' WHERE user='$groupId' AND id='$nId'");
	}

	function check_notification()
	{
		$this->load->model('go_dashboard');
		//$groupId  = $this->go_dashboard->go_get_groupid();
        $user = $this->session->userdata('user_name');
		$return = "";

	    $this->godb = $this->load->database('goautodialdb', true);

		$query = $this->godb->query("SELECT count(*) AS cnt FROM go_notifications WHERE user='$user' AND status='UNCLICKED'");
		$result = $query->row()->cnt;

		echo $result;
	}

	function update_notification()
	{
		$nId = $this->uri->segment(3);
		$this->load->model('go_dashboard');
		//$groupId  = $this->go_dashboard->go_get_groupid();
        $user = $this->session->userdata('user_name');

	    $this->godb = $this->load->database('goautodialdb', true);

		$query = $this->godb->query("UPDATE go_notifications SET status='CLICKED' WHERE user='$user' AND id='$nId'");
	}

        function control_panel(){
              $result = exec('/usr/share/goautodial/goautodialc.pl "/sbin/service asterisk status"');
              if(preg_match("/\brunning\b/i",$result)){
                    $cpanel['asterisk'] = "Running";
              } else {
                    $cpanel['asterisk'] = "Not Running";
              }
              $result = exec('/usr/share/goautodial/goautodialc.pl "/sbin/service mysqld status"');
              if(preg_match("/\brunning\b/i",$result)){
                    $cpanel['mysql'] = "Running";
              } else {
                    $cpanel['mysql'] = "Not Running";
              }
              $result = exec('/usr/share/goautodial/goautodialc.pl "/sbin/service httpd status"');
              if(preg_match("/\brunning\b/i",$result)){
                    $cpanel['httpd'] = "Running";
              } else {
                    $cpanel['httpd'] = "Not Running";
              }
              $result = exec('/usr/share/goautodial/goautodialc.pl "/sbin/service sshd status"');
              if(preg_match("/\brunning\b/i",$result)){
                    $cpanel['sshd'] = "Running";
              } else {
                    $cpanel['sshd'] = "Not Running $result";
              }
              if ((file_exists("/etc/sysconfig/network-scripts/ifcfg-eth0"))&&(!file_exists("/etc/sysconfig/network-scripts/ifcfg-eth1"))){
                  $result = exec('/usr/share/goautodial/goautodialc.pl "/etc/init.d/network status"');
	          if (preg_match_all("/[\w ]*eth0[\w ]*/", $result, $matches, PREG_OFFSET_CAPTURE) || (preg_match_all("/[\w ]*eth1[\w ]*/", $startme, $matches, PREG_OFFSET_CAPTURE))){
                       $cpanel['nic'] = 'Running';
                  } else {
                       $cpanl['nic'] = 'Not Running';
                  } 
              } else {
                  $cpanel['nic'] = "Not Running";
              }
              $cpanel['ftp'] = ftp_connect('localhost');

              echo json_encode($cpanel);
        }

        function cpanel(){
             $type = $this->uri->segment(3);
             $action = $this->uri->segment(4);
             if($type=='nic'){
                  $type = "network";
             }elseif($type=="asterisk"){
                 # exclusive start/reload for asterisk
                 if($action != "Reload"){
                     $result = shell_exec("/usr/share/goautodial/goautodialc.pl 'screen -ls'");
                     if(preg_match('/asterisk/',$result)){
                        exec("/usr/share/goautodial/goautodialc.pl \"kill -9 `ps aux | grep asterisk | grep -v -e grep | awk '{print $2}'`\"");
                        exec("/usr/share/goautodial/goautodialc.pl \"kill -9 `ps aux | grep astshell | grep -v -e grep | awk '{print $2}'`\"");
                        exec("/usr/share/goautodial/goautodialc.pl \"screen -wipe\"");
                     }
                     exec("/usr/share/goautodial/goautodialc.pl \"/usr/share/astguiclient/start_asterisk_boot.pl\"");
                 }else{
                        exec("/usr/share/goautodial/goautodialc.pl 'asterisk -rx \"reload\"'");
                 }
                 die("Success: $type {$action}ed");
             }
             exec("/usr/share/goautodial/goautodialc.pl '/sbin/service $type ".strtolower($action)."'");
             die("Success: $type {$action}ed");
        }

        function walk(){
             $this->load->model('go_dashboard');
             $this->go_dashboard->goautodial->trans_start();
                $this->go_dashboard->goautodial->where(array('account_num'=>$_POST['compid']));
                $result = $this->go_dashboard->goautodial->get('go_login_type')->result();
                if($result[0]->new_signup == 1){
                    $this->go_dashboard->goautodial->where(array('account_num'=>$_POST['compid']));
                    $this->go_dashboard->goautodial->update('go_login_type',array('new_signup'=>'0'));
                }
             $this->go_dashboard->goautodial->trans_complete();
             echo json_encode($result);
        }

        function walkupdate(){
            if(!empty($_POST['account'])){
                 $this->load->model('go_dashboard');
                 #$this->go_dashboard->goautodial->where(array('account_num'=>$_POST['account']));
                 $this->go_dashboard->goautodial->update('go_login_type',array('new_signup'=>"{$_POST['newsignup']}"));
            }
        }
}
