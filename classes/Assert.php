<?php
/*
 * Handles all database requests to do with asserts
 * Only failed asserts are stored to minimise data
 */
class Assert
{
  public $id = null;
  public $resultID = null;
  public $message = null;

  function __construct($data = array()){
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['resultID'])) $this->resultID = (int) $data['resultID'];
    if (isset( $data['message'])) $this->message =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "",
        $data['message'] );
  }

  public function createEntry( $data = array() ) {
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['resultID'])) $this->resultID = (int) $data['resultID'];
    if (isset( $data['message'])) $this->message =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/",
        "", $data['message'] );
    $this -> insert();
  }

  public static function getAssertSet( $resultID ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM asserts WHERE
            resultID = :resultID";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":resultID", $resultID, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $assert = new Assert( $row );
      $list[] = $assert;
    }

    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array( "results" => $list, "totalRows" => $totalRows[0] ));
  }

  public function insert() {
    if ( !is_null( $this->id ) ){
      trigger_error ( "Assert::insert(): Attempt to insert an Assert that
          object already has its ID property set (to $this->id).",
          E_USER_ERROR );
    }

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO asserts ( resultID, message )
            VALUES ( :resultID, :message )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":resultID", $this->resultID, PDO::PARAM_INT );
    $st->bindValue( ":message", $this->message, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }
}
?>
