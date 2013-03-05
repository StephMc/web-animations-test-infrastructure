<?php
require( "config.php" );
$log = '/var/www/web-animations-test-infrastructure/logfile.txt';
$fp = fopen($log, 'a') or exit("Can't open $log!");
fwrite($fp, "new test" . PHP_EOL);

$payload = isset($_POST["payload"]) ? $_POST["payload"] : "";
if($payload != ""){
  $payload = json_decode($payload, true);
  $commits = $payload["commits"];
  foreach ($commits as $commit) {
    $queuedRun = new QueuedRun;
    $a = array("sha1" => $commit["id"], "commitMessage" => $commit["message"], "commitURL" => $commit["url"]);
    $p = $queuedRun -> createEntry($a);
    fwrite($fp, "$p" . PHP_EOL);
  }
} else {
  echo "failure";
}

?>
