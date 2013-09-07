<?php
####################################################################################################
####  Name:             	go_logs_ce.php                                                      ####
####  Type:             	ci controller - administrator                                       ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_logs_ce extends Controller {
	var $userLevel;

    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_access','go_dashboard'));
		$this->load->library(array('session','commonhelper'));
		$this->load->helper(array('date','form','url','path'));
		$this->is_logged_in();
		$this->lang->load('userauth', $this->session->userdata('ua_language'));

		$this->userLevel = $this->session->userdata('users_level');
		$config['enable_query_strings'] = FALSE;
//		$this->config->initialize($config);
    }

	function index()
	{
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = "Admin Logs";
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);

		$data['userfulname'] = $this->go_dashboard->go_get_userfulname();

        $data['go_main_content'] = 'go_settings/go_logs';
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_logs()
	{
		$data['admin_logs'] = $this->go_dashboard->goautodial->query("SELECT * FROM go_action_logs ORDER BY event_date DESC LIMIT 1000;");
		
        $this->load->view('go_settings/go_logs_list',$data);
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$base = base_url();
			echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
// 			echo "<script>javascript: window.location = '$base'</script>";
			#echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
			die();
			#$this->load->view('go_login_form');
		}
	}
}