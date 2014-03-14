<?php

  error_reporting(0);

  $postcode = $_GET['postcode'];

  $postcode = preg_replace("/[^A-Z0-9]/","",strtoupper($postcode));

  mysql_connect("localhost", "cron", "1234");

  @mysql_select_db("asterisk") or die("Server Gone Away");

  $results = mysql_query("SELECT * FROM gab_post_codes WHERE post_code='". $postcode ."';");

  $add1   = mysql_result($results, 0, 'address_1');
  $add2   = mysql_result($results, 0, 'address_2');
  $town   = mysql_result($results, 0, 'town');
  $county = mysql_result($results, 0, 'county');

  mysql_close();

  print(json_encode(array(
      'add1'     => $add1,
      'add2'     => $add2,
      'town'     => $town,
      'county'   => $county,
      'postcode' => $postcode,
  )));

?>
