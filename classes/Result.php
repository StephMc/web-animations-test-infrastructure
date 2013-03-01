<?php

/**
 * Class to handle articles
 */

class Result
{
  // Properties

  /**
  * @var int The article ID from the database
  */
  public $id = null;

  /**
  * @var int The test run it belongs to
  */
  public $testRunID = null;

  /**
  * @var string Name of the test
  */
  public $testName = null;

  public $assertsPassed = null;

  function __construct($data = array()){
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['testRunID'])) $this->testRunID = (int) $data['testRunID'];
    if (isset( $data['testName'])) $this->testName =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['testName'] );
    if (isset( $data['assertsPassed'])) $this->assertsPassed =
      preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['assertsPassed'] );
  }

  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function createEntry( $data = array() ) {
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['testRunID'])) $this->testRunID = (int) $data['testRunID'];
    if (isset( $data['testName'])) $this->testName =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['testName'] );
    $this -> insert();
    return $this -> id;
  }

  public function update() {
    // Does the Article object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );

    // Update the Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE results SET assertsPassed = :assertsPassed WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":assertsPassed", $this->assertsPassed, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Returns all tests with a matching test run id
  *
  * @param int Optional The number of rows to return (default=all)
  * @param int Which run set to return
  * @return Array|false A two-element array : results => array, a list of Article objects; totalRows => Total number of articles
  */

  public static function getTestSet( $runID ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM results WHERE testRunID = :runID";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":runID", $runID, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $article = new Result( $row );
      $list[] = $article;
    }

    // Now get the total number of articles that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Inserts test results into the database, and sets its ID property.
  */

  public function insert() {

    // Does the Article object already have an ID?
    if ( !is_null( $this->id ) ){
      trigger_error ( "Article::insert(): Attempt to insert an Article that
          object already has its ID property set (to $this->id).", E_USER_ERROR );
    }

    // Insert the Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO results ( testRunID, testName ) VALUES ( :testRunID, :testName )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":testRunID", $this->testRunID, PDO::PARAM_INT );
    $st->bindValue( ":testName", $this->testName, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }

}

?>
