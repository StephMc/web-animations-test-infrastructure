<?php
require( "config.php" );
$nextRun = QueuedRun::getNextTest();
if($nextRun){
  // There's a test run to do
  // Create a new test run
  $run = new Run;
  $a = array("commitSHA" => $nextRun["sha1"], "commitMessage" => $nextRun["commitMessage"]);
  $runId = $run -> createEntry($a);
  $sha1 = $nextRun["sha1"];
  exec("bash /var/www/web-animations-test-infrastructure/triggerTests.sh $sha1 $runId ");
}
?>
