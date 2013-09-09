/* Webform Javascripts */
/* ******************* */

function getCustomerInfoValues()
{
	document.getElementById('title').value = parent.document.getElementById('title').value;
	document.getElementById('first_name').value = parent.document.getElementById('first_name').value;
	document.getElementById('last_name').value = parent.document.getElementById('last_name').value;
	document.getElementById('address1').value = parent.document.getElementById('address1').value;
	document.getElementById('address2').value = parent.document.getElementById('address2').value;
	document.getElementById('city').value = parent.document.getElementById('city').value;
	document.getElementById('postal_code').value = parent.document.getElementById('postal_code').value;
	document.getElementById('phone_number').value = parent.document.getElementById('phone_number').value;
  document.getElementById('alt_phone').value = parent.document.getElementById('alt_phone').value;
	document.getElementById('comments').value = parent.document.getElementById('comments').value;
}

function changeProgressStatus(msg)
{
  /*
  if(msg == 'Please Wait')
  {
    $("#SaveWebForm").attr("disabled", "disabled");
  }
  else
  {
    $("SaveWebForm").removeAttribute("disabled");
  }
  */
	document.getElementById('Status').innerHTML = msg;
}

function getEmailAddress()
{
	var emailValue = parent.document.getElementById('email').value;
	
	document.getElementById('Survey-Email').value = emailValue;
	
	if(emailValue != "")
	{
		var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		
		if(emailPattern.test(emailValue) == false)
		{
			alert('Email Address is Invalid');
		}
	}
	else
	{
		confirm('Are you sure they haven\'t got an email address?');
	}
}