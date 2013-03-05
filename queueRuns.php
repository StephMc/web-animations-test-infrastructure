<?php
require( "config.php" );
$payload = isset($_POST["payload"]) ? $_POST["payload"] : "";
if($payload != ""){
  $payload = json_decode($payload, true);
  $commits = $payload["commits"];
  foreach ($commits as $commit) {
    $queuedRun = new QueuedRun;
    $a = array("sha1" => $commit["id"], "commitMessage" => $commit["message"], "commitURL" => $commit["url"]);
    $queuedRun -> createEntry($a);
  }
}
?>