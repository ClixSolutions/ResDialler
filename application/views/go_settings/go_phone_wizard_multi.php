<?php
########################################################################################################
####  Name:             	go_phone_wizard_multi.php                                           ####
####  Type:             	ci views for phones - administrator                                 ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora					            	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
$NOW = date('Y-m-d');
?>
<style type="text/css">
#phoneTable input,
#phoneTable select {
/*	border: 1px solid #999; */
}

#campTable td{
	padding:0px 5px 0px 5px;
}

#saveButtons{
	float:right;
	width:150px;
	text-align:right;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

#saveButtons span{
	text-align:center;
	color:#7A9E22;
	cursor:pointer;
	width:40px;
}

#saveButtons span:hover{
	font-weight:bold;
}

::-webkit-input-placeholder { /* WebKit browsers */
    color:    #999;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color:    #999;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    color:    #999;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    color:    #999;
}

.modify-value {
    font-weight: bold;
    color: #7f7f7f;
}
</style>

<script>

   $(function(){
 
       $("#submit").click(function(){

           if($("#start_exten").val().length > 0){
    
                if(parseInt($("#start_exten").val()) > 999){
                    var $data = {count:$("#addcount").val(),start_exten:$("#start_exten").val(),protocol:$("#protocol").val()};
	            $("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	            $('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_phones_ce/go_phone_wizard/',$data).fadeIn("slow");
                }else{
                    $("#start_exten").css("border-color","red");
                }
           }else{
                $("#start_exten").css("border-color","red");
           }
       });

       $("#start_exten").keydown(function(event){
           $(this).css("border-color","#DFDFDF");
           if( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: Ctrl+X
            (event.keyCode == 88 && event.ctrlKey === true) || 
             // Allow: Ctrl+C
            (event.keyCode == 67 && event.ctrlKey === true) || 
             // Allow: Ctrl+V
            (event.keyCode == 86 && event.ctrlKey === true) || 
             // Allow: Ctrl+Z
            (event.keyCode == 90 && event.ctrlKey === true) || 
             // Allow: Ctrl+Y
            (event.keyCode == 89 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return true;
           }else{
                if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                    event.preventDefault(); 
                }
           }
       }); 
   });

</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1of2-navigation-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">Phone Wizard &raquo; Add New Phone</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top" style="padding-left:150px;">
                     <?$phoneCount = array(1=>1,2=>2,3=>3,4=>4,5=>5,
                                           6=>6,7=>7,8=>8,9=>9,10=>10,
                                           11=>11,12=>12,13=>13,14=>14,15=>15,
                                           16=>16,17=>17,18=>18,19=>19,20=>20)?>
                     <table>
                         <tr>
                           <td style="width:50%;text-align:right;"><strong>Additional Phone(s):</strong></td><td><?=form_dropdown('addcount',$phoneCount,null,'id="addcount"');?></td>
                         </tr>
                         <tr>
                           <td style="text-align:right;"><strong>Starting Extension:</strong></td><td><?=form_input('start_exten',null,"id='start_exten' size='5'")?> <i style="font-size:8px;">e.g. 8001</i></td>
                         </tr>
                         <tr>
                            <td><strong>Client Protocol</strong></td>
                            <td>
                              <?php
			   	$protocolArray = array('SIP'=>'SIP','IAX2'=>'IAX2');
				echo form_dropdown('protocol',$protocolArray,null,'id="protocol"');
			      ?>
                            </td>
                         </tr>
                     </table>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="submit" style="white-space: nowrap;">Next</span></span>
