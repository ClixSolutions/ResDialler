/* jQuery Calls */

function showHint(str, file, ajaxDiv)
{
	var xmlhttp;
	if (str.length==0)
  {
	  document.getElementById("txtHint").innerHTML="";
	  hideAjaxDiv(ajaxDiv);
	  return;
  }
	if (window.XMLHttpRequest)
  {
  	// code for IE7+, Firefox, Chrome, Opera, Safari
  	xmlhttp=new XMLHttpRequest();
  }
	else
  {
  	// code for IE6, IE5
  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  
	xmlhttp.onreadystatechange=function()
  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	  {
	  	gab_showDiv(ajaxDiv);
	  	document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	  }
  }
  
	xmlhttp.open("GET", "js/" + file + ".php?q="+str, true);
	xmlhttp.send();
}

/* Function has Error */
function gab_showDiv(divID)
{
  if (document.layers)
    document.layers[divID].display = "block";
  else
    document.getElementById(divID).style.display = 'block';
}


function hideAjaxDiv(divID)
{
	document.getElementById(divID).style.display = 'none';
}

function fillAjaxInput(field, value, ajaxDiv)
{
	fieldEl = document.getElementById(field);
  if (fieldEl)
    fieldEl.value = value;

  hideAjaxDiv(ajaxDiv);
}

$(document).ready(function() {
	$("#FindAddress").click(function() {
		$("#FindAddress-Dialog").dialog({
			title: 'Find an Address by Post Code',
			resizable: false,
			width: 460,
			buttons: {
				"Ok": function() {
          $("#address1").val($("#PC_Address1").val());
          $("#address2").val($("#PC_Address2").val());
          $("#city").val($("#PC_Town").val());
          $("#state").val($("#PC_County").val());
          $("#postal_code").val($("#PostCodeSearch").val());
					$(this).dialog("close");
			}, 
				"Cancel": function() { 
					$(this).dialog("close"); 
			} 
		}
		});
		$("#PostCodeSearch").val($("#postal_code").val());
	});
  
  $("#Save_to_Debtsolv_Form").submit(function()
  {
    $("#SaveWebForm").hide();
  });
});

function getAddress()
{
	var postCodeVal = document.getElementById('PostCodeSearch').value;
	var result;
	var address_1;
	
	$.post("webforms/js/post-code-search.php", { postCode: postCodeVal },
	  function(output)
		{
			address_1 = $("#House_Number_Name").val() + ', ' + output.address_1;
			 
			$("#PC_Address1").val(address_1);
			$("#PC_Address2").val(output.address_2);
			$("#PC_Town").val(output.town);
			$("#PC_County").val(output.county);
      
      result = 'Found: ' + address_1 + ', ' + output.address_2 + ', ' + output.town + ', ' + output.county; 
      
  	  $('#Post-Code-Result').html(result).show();
		},
	  'json');
}