<?php
############################################################################################
####  Name:             go_reports.php                                                  ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

$permissions = $this->commonhelper->getPermissions("reportsanalytics",$this->session->userdata("user_group"));
$pageid = $pagetitle;
switch ($pagetitle)
{
	case "agent_detail":
	case "agent_pdetail":
		$pagetitle = "Agent Stats Report";
		break;
	case "dispo":
		$pagetitle = "Dial Statuses Summary Report";
		break;
	case "sales_agent":
		$pagetitle = "Sales Per Agent Report";
		break;
	case "sales_tracker":
		$pagetitle = "Outbound/Inbound Sales Tracker Report";
		break;
	case "inbound_report":
		$pagetitle = "Inbound Report";
		break;
	case "call_export_report":
		$pagetitle = "Export Call Report";
		break;
	case "dashboard":
		$pagetitle = "Dashboard";
		break;
	case "limesurvey":
		$pagetitle = "Advance Script";
		break;
	default:
		$pagetitle = "Statistical Report";
}

$NOW = date('Y-m-d');
$oneWeekAgo = strtotime ( '-1 week' , strtotime ( $NOW ) ) ;
$cwd = getcwd();
if (!isset($request)) { $request = 'daily'; }
if (!isset($campaign_id) || $campaign_id=='null' || strlen($campaign_id) < 1) {
	$json_data[$from_date]['data'][] = "";
	write_file("$cwd/data/stats-$request.json",json_encode($json_data));
}
?>
<style type="text/css">
body{
	font-family:Verdana, Arial, Helvetica, sans-serif;
        height:90%;
}

.menuOn{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:8px 10px 11px 10px;";
}
else
{
	echo "padding:8px 10px 9px 10px;";
}
?>
	color:#777;
	font-size:11px;
	cursor:pointer;
	background-color:#FFF;
	border-left:#CCC 1px solid;
	color:#000;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:8px 10px 10px 10px;";
}
else
{
	echo "padding:8px 10px 8px 10px;";
}
?>
	color:#777;
	font-size:11px;
	cursor:pointer;
	background-color:#efefef;
	border-left:#CCC 1px solid;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

/*.menuOff:hover{
	background-color:#dfdfdf;
	color:#000;
}*/

#account_info_status .table_campaigns {
	margin:1px 0px 0px -10px;
	padding:7px 11px 0px 9px;
	border-top:#CCC 1px solid;
	width:100%;
	text-align:center;
	z-index:99;
}
</style>
<div id='outbody' class="wrap">
<div id="icon-repanalytic" class="icon32">
</div>
<h2><? echo $bannertitle; ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			
			<!-- START LEFT WIDGETS -->
			<div id="cnt-reports" class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">
					
					<!-- GO REPORTS WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv">
						</div>
						<div class="hndle">
							&nbsp;<!--Reports &amp; Analytics-->
						</div>
						<div class="inside">
							
							<div style="float:right;margin-top:<?php echo (preg_match("/^Windows/",$userOS)) ? "-22px" : "-21px"; ?>;margin-right:5px;" id="request_tab">
								<div id="widgetDate" style="cursor:pointer;display: none;">
									<span><? echo date('Y-m-d'); ?> to <? echo date('Y-m-d'); ?></span>
								</div>
								<span id="request" style="display:none;">daily</span><span id="daily" class="tabtoggle menuOn">Daily</span><span id="weekly" class="tabtoggle menuOff">Weekly</span><span id="monthly" class="tabtoggle menuOff" style="border-right:#CCC 1px solid;">Monthly</span>
							    <div id="widgetField" class="hovermenu" style="float:right;<?php echo (preg_match("/Chrome/",$_SERVER['HTTP_USER_AGENT'])) ? "margin-top:-11px;" : ""; ?>">
								<a href="#" id="selectDate" class="toolTip" title="Select date range">Select date range</a>
							    </div>
							</div>
							<div style="position:absolute;top:-8px;left:-6px;">
							<span id="pagetitle" class="tabmenu" title="stats"><? echo $pagetitle; ?></span>
							<span id="select_campaign" class="tabmenu" title="Select a Campaign">Select a Campaign</span>
							</div>
							<div class="table_campaigns">
								<div id="table_reports">
								</div>
							</div>
							
							<div class="widgets-content-text">
							<br class="clear">								

								<br class="clear">
							<br class="clear">
							<!--
							<p>
								Configure this widget's (today's status) settings.
								<a href="" class="button">Configure</a>
							</p>
							<p>
								You are using GoAutoDial 3.0.1.
								<a href="" class="button">Update to 3.1.3</a>
							</p>
							-->
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<!-- END LEFT WIDGETS -->
	
			<!-- START RIGHT WIDGETS -->
			<div class="postbox-container" style="width:49.5%;display:none;">
				<div class="meta-box-sortables ui-sortable">

				</div>
			</div>
			<!-- END RIGHT WIDGETS -->
	
			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column3-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column4-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
		</div><!-- dashboard-widgets -->
	</div><!-- dashboard-widgets-wrap -->
</div><!-- wrap -->


							<?
echo "<div id='popup_analytics_all' class='popup_block'>\n";
?>

			<div class="table go_dashboard_analytics_in_popup_cmonth_daily" id="go_dashboard_analytics_in_popup_cmonth_daily">
			</div>
	
			<div class="table go_dashboard_analytics_in_popup_weekly" id="go_dashboard_analytics_in_popup_weekly">
			</div>

			<div class="table go_dashboard_analytics_in_popup_hourly" id="go_dashboard_analytics_in_popup_hourly">
			</div>

<?
echo "</div>\n";
?>

<div id='go_reports_menu' class='go_reports_menu'>
<ul>
<?php
if ($permissions->reportsanalytics_statistical_report=='Y') { echo '<li class="go_reports_submenu" title="Statistical Report" id="stats">Statistical Report</li>'; }
if ($permissions->reportsanalytics_agent_time_detail=='Y') { echo '<li class="go_reports_submenu" title="Agent Time Detail" id="agent_detail">Agent Time Detail</li>'; }
if ($permissions->reportsanalytics_agent_performance_detail=='Y') { echo '<li class="go_reports_submenu" title="Agent Performance Detail" id="agent_pdetail">Agent Performance Detail</li>'; }
if ($permissions->reportsanalytics_dial_status_summary=='Y') { echo '<li class="go_reports_submenu" title="Dial Statuses Summary" id="dispo">Dial Statuses Summary</li>'; }
if ($permissions->reportsanalytics_sales_per_agent=='Y') { echo '<li class="go_reports_submenu" title="Sales Per Agent" id="sales_agent">Sales Per Agent</li>'; }
if ($permissions->reportsanalytics_sales_tracker=='Y') { echo '<li class="go_reports_submenu" title="Sales Tracker" id="sales_tracker">Sales Tracker</li>'; }
if ($permissions->reportsanalytics_inbound_call_report=='Y') { echo '<li class="go_reports_submenu" title="Inbound Call Report" id="inbound_report">Inbound Call Report</li>'; }
if ($permissions->reportsanalytics_export_call_report=='Y') { echo '<li class="go_reports_submenu" title="Export Call Report" id="call_export_report">Export Call Report</li>'; }
if ($permissions->reportsanalytics_dashboard=='Y') { echo '<li class="go_reports_submenu" title="Dashboard" id="dashboard">Dashboard</li>'; }
if ($permissions->reportsanalytics_advance_script=='Y') { echo '<li class="go_reports_submenu" title="Advance Script" id="limesurvey">Advance Script</li>'; }
?>
</ul>
</div>

<!-- Calendar-->
<div id="widgetCalendar">
</div>

<!-- Campaign IDs -->
<div id="campaign_ids" class="go_campaign_menu">
<ul>
<?
foreach ($campaign_ids as $item)
	{
		echo "<li class=\"go_campaign_submenu\" style=\"padding: 3px 10px 3px 3px; margin: 0px; white-space: nowrap;\" title=\"".$item->campaign_id."\">".$item->campaign_name."&nbsp;&nbsp;</li>\n";
	}
?>
</ul>
</div>

<!-- Inbound Campaign IDs -->
<div id="inbound_groups" class="go_campaign_menu">
<ul>
<?
foreach ($inbound_groups as $item)
	{
		echo "<li class=\"go_campaign_submenu\" style=\"padding: 3px 10px 3px 3px; margin: 0px;\" title=\"".$item->group_id."\">".$item->group_id."</li>\n";
	}
?>
</ul>
</div>


<!-- Debug-->
<div id="showDebug">
</div>

<div id="hiddenToggle" style="visibility:hidden"></div>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
