<?php
require( "config.php" );
$nextRun = QueuedRun::getNextTest();
if($nextRun){
  // There's a test run to do
  $sha1 = $nextRun["sha1"];
  exec("bash /var/www/web-animations-test-infrastructure/triggerTests.sh $sha1 ");
}
?>
