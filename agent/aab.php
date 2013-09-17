<?php
// Script to automatically send an answerphone to a pre-recoded message and then disposition the agent

$user = $_GET['user'];

$send_vm_result = file_get_contents('http://dialler.res.clixconnect.net/agent/api.php?source=testingapi&user=apiuser&pass=apiuser&agent_user=' . $user . '&function=external_hangup&value=1');

$dispo_result = file_get_contents('http://dialler.res.clixconnect.net/agent/api.php?source=testingapi&user=apiuser&pass=apiuser&agent_user='.$user.'&function=external_status&value=AAB');

print "Call with agent ".$user." has been ended and dispositioned";

?>
