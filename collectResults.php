<?php
/*
 * When the tests get trigged the results get sent to this page
 */
require( "config.php" );
$log = '/var/www/web-animations-test-infrastructure/logfile.txt';
$fp = fopen($log, 'a') or exit("Can't open $log!");
fwrite($fp, "collect Test" . PHP_EOL);

$data = isset( $_POST["data"] ) ? $_POST["data"]  : "";
$info =  json_decode($data, true);

if ( $info["type"] == "finished"){
  fwrite($fp, "finished" . PHP_EOL);
  $b = $info["testRunId"];
  fwrite($fp, "$b" . PHP_EOL); 
  // Update the run with pass/fail rate
  $run = Run::getById($b);
  $run->testsPassed = $info["testsPassed"];
  $a = $run->id;
  fwrite($fp, "$a" . PHP_EOL);
  $run->update(); 
  // Kill the Xvfb and chrome then check for the next test
  //exec("touch hey.txt");
  exec("bash /var/www/web-animations-test-infrastructure/resetAndTrigger.sh ");
} else if ( $info["type"] == "result"){
  fwrite($fp, "This is a result" . PHP_EOL);
  // Create new result entry
  $result = new Result;
  $b = array("testRunID" => $info["testRunID"], "testName" => $info["testName"]);
  $createdResultID = $result -> createEntry($b);
  
  //Create entry for each assert in the test
  $failed = 0;
  $total = $info["numberOfAsserts"];
  foreach ($info["asserts"] as $a) {
    $assert = new Assert;
    $c = array("resultID" => $createdResultID, "message" => $a["message"]);
    $assert -> createEntry($c);
    $failed++;
  }
  $passed = $total - $failed;
  $result -> assertsPassed = "$passed out of $total";
  $result -> update();
} 
?>
