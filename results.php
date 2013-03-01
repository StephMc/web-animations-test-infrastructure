<?php
// When the tests get trigged the results get sent to this page
require( "config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";

switch ( $action ) {
  case 'result':
    listResults();
    break;
  default:
    listRuns();
}

function listRuns(){
  // Get the desired test run
  $query = Run::getList();
  $runs = $query["results"];
  foreach ( $runs as $run) { ?>
        <p>
          <h2>
            <a href="results.php?action=result&amp;runId=<?php echo $run->id; ?>"><?php echo $run -> id?></a>
          </h2>
          <p>Run time: <?php echo $run -> runTime;?> <br> Commit SHA1: <?php echo $run->commitSHA;?></p>
        </p>
  <?php }
}

function listResults(){
  $desiredId = $_GET["runId"];
  $query = Result::getTestSet($desiredId);
  $resultSet = $query["results"];

  foreach ($resultSet as $result) { ?>
        <p>
          <h2>
            <a href="<?php echo TEST_PATH;?>/<?php echo $result->testName; ?>"><?php echo $result->testName;?></a>
          </h2>
          <p>Id: <?php echo $result->id;?> <br> Test Run Id: <?php echo $result->testRunID;?></p>
        </p>
  <?php }
}
?>
