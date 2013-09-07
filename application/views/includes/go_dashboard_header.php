<?php
########################################################################################################
####  Name:             	go_dashboard_header.php         	                            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
if ( empty($folded) )
{	
	$folded='';
	$foldlink = '../go_site/fold_me';
}

#$uname = $this->session->userdata('user_name');
$is_logged_in = $this->session->userdata('is_logged_in');
			
if ($logo==true){
$pagelogo=$logo;
$pagetitle=$title;
}
else{
$pagelogo="goadmin_logo.png";
$pagetitle="GoAdmin &reg; 3.0";
}
			

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $pagetitle; ?></title>
<link rel="shortcut icon" href="<?php echo $base; ?>img/gologoico.ico" />

<?
require_once($cssloader);
require_once($jsheaderloader);
?>



<script>
<!--//Global variables //-->
<?php                                                    
      if(!is_null($account)){                                                      
           echo "var account = '$account';";
      }else{                                                     
           echo "var account = '';";
      }                                                                         
?>
</script>
</head>
<body style="cursor: auto;" class="wp-admin no-js js <? echo $folded; ?> index-php">

<?
require_once($jsbodyloader);
?>




<div id="middle-head">
	<div id="wpcontent">
		<div id="wphead">
			
			
	<?
	if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$home="../";
		}
		else
		{
			$home="../dashboard";
		}
		//echo $this->is_logged_in();

	?>
			
			
			
			
			<div id="header-logo">
				<a href="<? echo $home;?>" title="Powered by GoAutoDial">
					<img src="<? echo $base; ?>img/<? echo $pagelogo;?>" />
				</a>
			</div>

        <?if(isset($hideinfo)):
             $hideThis = "style='display:none;'";
          else:
             $thidThis = "";
          endif;?>
	<div id="screen-options-link-wrap" <?=$hideThis?>>
				<a href="#screen-options" id="show-settings-link" title="Settings" class="show-settings">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</a>
			</div>





	<div id="spotlight-open-link-wrap"  <?=$hideThis?>>		
		<a href="#spotlight-open" id="spotlight-open-link" title="Search" class="show-settings">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</a>	
	</div>



	<div id="notification-open-link-wrap" <?=$hideThis?>>		
		<a href="#notification-open" id="notification-open-link" title="Notification" class="show-notification">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</a>	
	</div>




			<div id="wphead-info" <?=$hideThis?>>
				<div id="user_info">
					<div>
						<a id="clockbox" ></a>&nbsp;<b>|</b>&nbsp;Hello, <a href="<?=$base?>users" title="Edit your profile"><? echo $userfulname ?></a>&nbsp;<b>|</b>&nbsp;<a href="<? echo $base; ?>logout" title="Logout">Logout</a>
					</div>
				</div>
			</div>
			
			<?
			if ($adminheaderlink==1){
		?>		
			<div id="wphead-info" >
				<div id="user_info">
					<div>
						<a href="<? echo $home;?>"  style="color:#FFFFFF;text-decoration:none;" title="Go Back">Go Back</a>
					</div>
				</div>
			</div>
				
			<?	
				
			}
			
			
			
			?>
			
	
			
			
			
			
		</div>
	</div>

               <script>
               $(function(){
                    $("#inputString").keyup(function(event){

                        var keycode = (event.keyCode ? event.keyCode : event.which);
                        var inputString = $(this).val();
  	                var dataString =  '/' +  inputString;
                        if(keycode === 13){

                             if(inputString.length === 0){
                                 inputString = 0;
                             }
                             window.location.href="<?=$base?>index.php/search/"+inputString+"/2";

                        } else {

                            if(inputString.length > 0){
                                $.post(
                                       "<?=$base?>index.php/go_site/go_dashboard_search"+dataString,
                                       function(data){

                                             var status = data; 	
		                             $("#suggestions").show();		
		                             $("#autoSuggestionsList").fadeIn("fast").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');	
	
		
		                             if (data.length > 2)
		                             {
		                                 $('#autoSuggestionsList').load('<?=$base?>index.php/go_site/go_dashboard_search'+dataString).fadeIn("fast");	
		                             }
		                             else{
		                                 $('#suggestions').hide();
		                                 $('#autoSuggestionsList').html();
		                             }

                                       }
                                );
                            }

                         }

                    });

					// Check Screen Size
					var minWidth = 1000;
					var curWidth = $(window).width();
					if ($(window).width() < minWidth)
					{
						$('.col1').attr('checked',true);
						$('.col2').removeAttr('checked');
					} else {
						$('.col1').removeAttr('checked');
						$('.col2').attr('checked',true);
					}
					// Screen Resize
					$(window).resize(function()
					{
						if ($(window).width() != curWidth) {
							if ($(window).width() < minWidth)
							{
								$('.col1').attr('checked',true);
								$('.col2').removeAttr('checked');
							} else {
								$('.col1').removeAttr('checked');
								$('.col2').attr('checked',true);
							}
							curWidth = $(window).width();
						}
					});
               });
               </script>

				<!--<form id="searchform">-->

			<div id="spotlight-open-wrap" class="hidden">
						
						
			<!--<b> &nbsp;&nbsp;&nbsp;Search: </b> <input size="25" id="inputString" name="inputString" onkeyup="lookup(this.value);" onblur="fill();" type="text">-->
			<b> &nbsp;&nbsp;&nbsp;Search: </b> <input size="25" id="inputString" name="inputString"  type="text">
			
			</div>
			
			
			
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<div class="suggestionList" id="autoSuggestionsList" >
				</div>
			</div>
			
							<!--</form>-->

			

<div id="wpbody-content">
	<div id="screen-dmeta">
		<div style="display: none;" id="screen-options-wrap" class="hidden">
			<form id="adv-settings" action="" method="post">
				<h5>Show on screen</h5> 
				<div class="metabox-prefs">
				<label for="dashboard_todays_status-hide">
					<input class="hide-postbox-tog" name="dashboard_todays_status-hide" id="dashboard_todays_status-hide" value="dashboard_todays_status" checked="checked" type="checkbox">
					Today's Status
				</label>
				<label for="dashboard_server_statistics-hide">
					<input class="hide-postbox-tog" name="dashboard_server_statistics-hide" id="dashboard_server_statistics-hide" value="dashboard_server_statistics" checked="checked" type="checkbox">
					Server Statistics
						<span class="postbox-title-action">
							<a href="" class="edit-box open-box">Configure</a>
						</span>
				</label>
				<label for="dashboard_controls-hide">
					<input class="hide-postbox-tog" name="dashboard_controls-hide" id="dashboard_controls-hide" value="dashboard_controls" checked="checked" type="checkbox">
					System Services
						<span class="postbox-title-action">
							<a href="" class="edit-box open-box">Configure</a>
						</span>
				</label>
				<label for="dashboard_accounts-hide">
                                        <input class="hide-postbox-tog" name="dashboard_accounts-hide" id="dashboard_accounts-hide" value="account_info_status" checked="checked" type="checkbox">
                                        Account Info
                                                <span class="postbox-title-action">
                                                        <a href="" class="edit-box open-box">Configure</a>
                                                </span>
                                </label>
				<label for="dashboard_agents_status-hide">
					<input class="hide-postbox-tog" name="dashboard_agents_status-hide" id="dashboard_agents_status-hide" value="dashboard_agents_status" checked="checked" type="checkbox">
					Agent's Status
						<span class="postbox-title-action">
							<a href="?edit=dashboard_agents_status#dashboard_agents_status" class="edit-box open-box">Configure</a>
						</span>
				</label>
				<label for="dashboard_plugins-hide" style="display:none;">
					<input class="hide-postbox-tog" name="dashboard_plugins-hide" id="dashboard_plugins-hide" value="dashboard_plugins" checked="checked" type="checkbox">
					Plugins
				</label>
				<label for="dashboard_analytics-hide">
					<input class="hide-postbox-tog" name="dashboard_analytics-hide" id="dashboard_analytics-hide" value="dashboard_analytics" checked="checked" type="checkbox">
					GO Analytics
				</label>
				<!-- <label for="dashboard_goautodial_news-hide">
					<input class="hide-postbox-tog" name="dashboard_goautodial_news-hide" id="dashboard_goautodial_news-hide" value="dashboard_goautodial_news" checked="checked" type="checkbox">
					GoAutoDial News
						<span class="postbox-title-action">
							<a href="?edit=dashboard_goautodial_news#dashboard_goautodial_news" class="edit-box open-box">Configure</a>
						</span>
				</label>
				<label for="dashboard_goautodial_forum-hide">
					<input class="hide-postbox-tog" name="dashboard_goautodial_forum-hide" id="dashboard_goautodial_forum-hide" value="dashboard_goautodial_forum" checked="checked" type="checkbox">
					GoAutoDial Community & Forum
					<span class="postbox-title-action">
						<a href="?edit=dashboard_goautodial_forum#dashboard_goautodial_forum" class="edit-box open-box">Configure</a>
					</span>
				</label> -->
				<br class="clear">
				</div>

                                <div style="width:50%;float:left;">
				<h5>Screen Layout</h5>
				<div class="columns-prefs">Number of Columns:
				<label>
					<input name="screen_columns" value="1" type="radio"> 1
				</label>
				<label>
					<input name="screen_columns" value="2" checked="checked" type="radio"> 2
				</label>
				<!--
				<label>
					<input name="screen_columns" value="3" type="radio"> 3
				</label>
				<label>
					<input name="screen_columns" value="4" type="radio"> 4
				</label>
				-->
				</div>
                                </div>
                                <div style="float:left;width:auto;">
                                    <h5>Introduction Help</h5>
                                    <label>
                                        <input class="hide-postbox-tog" name="walk" id="walk" value="1" type="checkbox">
                                     </label>
                               </div>
                               <br class="clear"/>
			</form>
		</div>
	



	</div>


			
			
			
			

			
			
			
			
			
			



		

</div>




		

</div>





