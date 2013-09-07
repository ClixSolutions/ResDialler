<?php
####################################################################################################
####  Name:             	go_logs.php                                                         ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
?>
<script src="<? echo $base; ?>js/jscolor/jscolor.js" type="text/javascript"></script>
<style type="text/css">
body, table, td, textarea, input, select, div, span{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:13px;
	font-stretch:normal;
}

body{height:90%;}

a:hover {
	color:#F00;
	cursor:pointer;
}

#mainTable th {
	text-align:left;
}

.menuOn{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:6px 10px 7px 10px;";
}
else
{
	echo "padding:6px 10px 6px 10px;";
}
?>
	color:#777;
	font-size:13px;
	cursor:pointer;
	background-color:#FFF;
	border-right:#CCC 1px solid;
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
	echo "padding:6px 10px 6px 10px;";
}
else
{
	echo "padding:6px 10px 5px 10px;";
}
?>
	color:#555;
	font-size:13px;
	cursor:pointer;
	background-color:#efefef;
	border-right:#CCC 1px solid;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff:hover{
	background-color:#dfdfdf;
	color:#000;
}

#account_info_status .table_container {
	margin:9px 0px 0px -10px;
	padding:7px 10px 0px 9px;
	border-top:#CCC 1px solid;
	width:100%;
}

.rightdiv{
	cursor:pointer;
	color:#555;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.hideSpan {
	display:none;
}

/* Table Sorter */
table.tablesorter thead tr .header {
	text-align: left;
	cursor: pointer;
}

table.tablesorter tbody tr.odd td {
	background-color:#EFFBEF;
}

table.tablesorter tbody tr.even td {
	background-color:#E0F8E0;
}
</style>
<script>
$(function()
{
	$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#table_container').load('<? echo $base; ?>index.php/go_logs_ce/go_get_logs/');
});
</script>
<div id='outbody' class="wrap">
<div id="icon-logs" class="icon32">
</div>
<h2><? echo $bannertitle; ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<!-- START LEFT WIDGETS -->
			<div class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">

					<!-- GO WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv toolTip" style="display: none;" id="search_logs" title="Search Logs">
                        	Search Logs <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="hndle">
							<span><span id="title_bar" />&nbsp;</span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</div>
						<div class="inside">

                            <div style="margin:<?php echo (preg_match("/^Windows/",$userOS)) ? "-23px" : "-22px"; ?> 0px -2px -10px;" id="request_tab"><span id="showList" class="tabtoggle menuOn">Logs</span><span id="request" style="display:none;">showList</span></div>

							<div id="table_container" class="table_container">
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

<!-- Overlay1 -->
<div id="overlay" style="display:none;"></div>
<div id="box">
<a id="closebox" class="toolTip" title="CLOSE"></a>
<div id="overlayContent"></div>
</div>

<!-- Debug-->
<div id="showDebug"></div>

<div id="hiddenToggle" style="visibility:hidden"></div>
<div id="wizardSpan" style="visibility:hidden">false</div>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
