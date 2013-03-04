<?php
$payload = isset($_POST["payload"]) ? $_POST["payload"] : "";
if($payload != ""){
  $payload = json_decode($payload, true);
  $sha1 = $payload["after"];
  //echo $sha1;
} else {
  $sha1 = "";
}
exec("bash /var/www/web-animations-test-infrastructure/triggerTests.sh $sha1 ");
?>
