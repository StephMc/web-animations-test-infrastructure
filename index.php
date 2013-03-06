<?php
require( "config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";

switch ( $action ) {
  case 'result':
    listResults();
    break;
  case 'assert':
    listAsserts();
    break;
  default:
    listRuns();
}

function listRuns(){
  $query = Run::getList();
  $runs = $query["results"];
  foreach ( $runs as $run) { ?>
        <p>
          <h2>
            <a href="?action=result&amp;runId=<?php echo $run->id; ?>"><?php echo $run -> commitMessage?></a>
          </h2>
          <p>Run time: <?php echo $run -> runTime; ?> <br> 
          Commit SHA1: <a href="https://github.com/StephMc/web-animations-test-framework/commit/<?php echo $run->commitSHA;?>">
		       <?php echo $run->commitSHA;?></a></p>
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
            <a href="?action=assert&amp;resultId=<?php echo $result->id; ?>"><?php echo $result->testName;?></a>
          </h2>
          <p> Amount Passed: <?php echo $result->assertsPassed;?></p>
        </p>
  <?php }
}

function listAsserts(){
  $desiredId = $_GET["resultId"];
  //print_r($desiredId);
  $query = Assert::getAssertSet($desiredId);
  $assertSet = $query["results"];
  $result = Result::getById($desiredId);

  ?> <h1><?php echo $result->testName?></h1>
  <p> Amount Passed: <?php echo $result->assertsPassed ?> </p> <?php
  foreach ($assertSet as $assert) { ?>
        <p> Error Message: <?php echo $assert->message;?> </p>
  <?php }
}
?>
