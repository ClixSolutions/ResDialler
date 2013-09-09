<?php
function customer_info_form()
{
	?>
	<input type="hidden" name="data[title]" id="title" />
	<input type="hidden" name="data[first_name]" id="first_name" />
	<input type="hidden" name="data[last_name]" id="last_name" />
	<input type="hidden" name="data[address1]" id="address1" />
	<input type="hidden" name="data[address2]" id="address2" />
	<input type="hidden" name="data[city]" id="city" />
	<input type="hidden" name="data[postal_code]" id="postal_code" />
	<input type="hidden" name="data[phone_number]" id="phone_number" />
  <input type="hidden" name="data[alt_phone]" id="alt_phone" />
	<input type="hidden" name="data[comments]" id="comments" />
	<?php
}
?>