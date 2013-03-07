<?php
class Run {
  public $id = null;
  public $runTime = null;
  public $commitSHA = null;
  public $commitMessage = null;
  public $testsPassed = null;

  function __construct($data = array()){
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['runTime'] ) ) $this->runTime = $data['runTime'];
    if ( isset( $data['commitSHA'] ) ) $this->commitSHA =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitSHA'] );
    if ( isset( $data['commitMessage'] ) ) $this->commitMessage =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitMessage'] );
    if ( isset( $data['testsPassed'] ) ) $this->testsPassed =
      preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['testsPassed'] );
  }

  public function createEntry( $data = array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['runTime'] ) ) $this->runTime = (int) $data['runTime'];
    if ( isset( $data['commitSHA'] ) ) $this->commitSHA =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitSHA'] );
    if ( isset( $data['commitMessage'] ) ) $this->commitMessage =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitMessage'] );
    if ( isset( $data['testsPassed'] ) ) $this->testsPassed =
      preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['testsPassed'] );
    $this -> insert();
    return $this -> id;
  }

  public static function getById($runID) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM runs WHERE id = :runID";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":runID", $runID, PDO::PARAM_INT );
    $st->execute();
    
    $row = $st->fetch();
    $run = new Run($row);

    $conn = null;
    return ($run);
  }

  public function update() {
    if ( is_null( $this->id ) ) {
      trigger_error ( "Run::update(): Attempt to update an Run object
        that does not have its ID property set.", E_USER_ERROR );
    }

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE runs SET testsPassed = :testsPassed WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":testsPassed", $this->testsPassed, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

  public static function getList( $numRows=1000000, $order="id DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM runs
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $run = new Run($row);
      $list[] = $run;
    }

    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }

  public function insert() {
    if ( !is_null( $this->id ) ) {
      trigger_error ( "Article::insert(): Attempt to insert an Article object
        that already has its ID property set (to $this->id).", E_USER_ERROR );
    }

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO runs ( runTime, commitSHA, commitMessage, testsPassed ) 
            VALUES ( date('Y-m-d H:i:s'), :commitSHA, :commitMessage, :testsPassed )";
    $st = $conn->prepare ( $sql );
    //$st->bindValue( ":runTime", $this->runTime, PDO::PARAM_INT );
    $st->bindValue( ":commitSHA", $this->commitSHA, PDO::PARAM_STR );
    $st->bindValue( ":commitMessage", $this->commitMessage, PDO::PARAM_STR );
    $st->bindValue( ":testsPassed", $this->testsPassed, PDO::PARAM_STR );
    $st->execute();
    $this -> id = $conn->lastInsertId();
    $conn = null;
  }
}
?>
