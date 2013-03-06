<?php
require( "config.php" );
$log = '/var/www/web-animations-test-infrastructure/logfile.txt';
$fp = fopen($log, 'a') or exit("Can't open $log!");
fwrite($fp, "new test" . PHP_EOL);

$nextRun = QueuedRun::getNextRun();
$a = $nextRun -> commitMessage;
fwrite($fp, "$a" . PHP_EOL);

if($nextRun){
  // There's a test run to do
  // Create a new test run
  $run = new Run;
  $a = array("commitSHA" => $nextRun -> sha1, "commitMessage" => $nextRun -> commitMessage);
  $runId = $run -> createEntry($a);
  $sha1 = $nextRun -> sha1;
  // Remove the newly created run from the queuedRun table
  // Delete the QueuedRun that was just completed
  $nextRun -> delete();
  exec("bash /var/www/web-animations-test-infrastructure/triggerTests.sh $sha1 $runId ");
}
?>
