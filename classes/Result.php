<?php
class Result
{
  public $id = null;
  public $testRunID = null;
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
  
  public function createEntry( $data = array() ) {
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['testRunID'])) $this->testRunID = (int) $data['testRunID'];
    if (isset( $data['testName'])) $this->testName =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['testName'] );
    $this -> insert();
    return $this -> id;
  }

  public static function getById($resultId) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM results WHERE id = :resultId";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":resultId", $resultId, PDO::PARAM_INT );
    $st->execute();

    $result = null;
    while ( $row = $st->fetch() ) {
      $result = new Result( $row );
    }

    $conn = null;
    return ($result);
  }

  public function update() {
    if ( is_null( $this->id ) ) trigger_error ( "Result::update(): Attempt to
        update an Result object that does not have its ID property set.", E_USER_ERROR );

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE results SET assertsPassed = :assertsPassed WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":assertsPassed", $this->assertsPassed, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

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

    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }

  public function insert() {
    if ( !is_null( $this->id ) ){
      trigger_error ( "Article::insert(): Attempt to insert an Article that
          object already has its ID property set (to $this->id).", E_USER_ERROR );
    }

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
