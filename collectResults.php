// When the tests get trigger the results get sent to this page
<?php

require( "config.php" );
$data = isset( $_POST['data'] ) ? $_POST['data'] : "";

if ($data != ""){
  $info =  json_decode($data);
  if ( $info["type"] == "start"){
    // New incomming test, create new run for it
    // Send back the run ID
    $run = new Run;
    $createdID = $run -> createEntry(date(), $info["commitSHA"]);
    // Return the id of the run database entry so all related results
    // are attached to the right run instance
    echo $createdID;
  } else if ( $info["type"] == "finished"){
    // Kill the Xvfb after x seconds to allow packets to arrive out of order
  } else if ( $info["type"] == "result"){
    // Add the incomming result to the database
    // Create new result entry
    $result = new Result;
    $createdResultID = $result -> createEntry($info["testRunID"], $info["testName"]);

    // Create entry for each assert in the test
    foreach ($info["asserts"] as $a) {
      $assert = new Assert;
      $assert -> createEntry($createdResultID, $a["result"], $a["message"]);
    }
  } else {
    // This should never happen. Output to the error log
    $e = "collectResults.php: Couldn't read the data sent."
    exec("bash errorLog.sh $e");
  }
}

?>