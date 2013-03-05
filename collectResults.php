<?php
/*
 * When the tests get trigged the results get sent to this page
 */
require( "config.php" );

$data = isset( $_POST["data"] ) ? $_POST["data"]  : "";
$info =  json_decode($data, true);

if ( $info["type"] == "finished"){
  // Kill the Xvfb and chrome then check for the next test
  exec("bash /var/www/web-animations-test-infrastructure/resetAndTrigger.sh ");
} else if ( $info["type"] == "result"){
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
