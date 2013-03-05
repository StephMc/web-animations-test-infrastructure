<?php
// When the tests get trigged the results get sent to this page
require( "config.php" );

//print_r("testing im here");
//print_r($_POST);

$data = isset( $_POST["data"] ) ? $_POST["data"]  : "";
// Do something if the string is ""
$info =  json_decode($data, true);
//print_r($info);

if ($info["type"] == "start"){
  // New incomming test, create new run for it
  // Send back the run ID
  $run = new Run;
  $a = array("commitSHA" => $info["commitSHA"]);
  //print_r($a);
  $createdID = $run -> createEntry($a);
  // Return the id of the run database entry so all related results
  // are attached to the right run instance
  echo $createdID;
} else if ( $info["type"] == "finished"){
  // Kill the Xvfb after x seconds to allow packets to arrive out of order
} else if ( $info["type"] == "result"){
  // Add the incomming result to the database
  // Create new result entry
  $result = new Result;
  $b = array("testRunID" => $info["testRunID"], "testName" => $info["testName"]);
  //print_r($b);
  $createdResultID = $result -> createEntry($b);
  //print_r($createdResultID);
  //Create entry for each assert in the test
  $failed = 0;
  $total = $info["numberOfAsserts"];
  foreach ($info["asserts"] as $a) {
    $assert = new Assert;
    $c = array("resultID" => $createdResultID, "message" => $a["message"]);
    print_r($c);
    $assert -> createEntry($c);
    $failed++;
  }
  $passed = $total - $failed;
  $result -> assertsPassed = "$passed out of $total";
  $result -> update();
} else {
  // This should never happen. Output to the error log
  $e = "collectResults.php: Couldn't read the data sent.";
  exec("bash errorLog.sh $e");
}

?>