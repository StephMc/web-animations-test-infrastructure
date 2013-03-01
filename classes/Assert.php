<?php

/**
 * Class to handle articles
 */

class Assert
{
  // Properties

  /**
  * @var int The assert ID from the database
  */
  public $id = null;

  /**
  * @var int The result it belongs to
  */
  public $resultID = null;

  /**
  * @var boolean If the test passed
  */
  public $result = null;

  /**
  * @var string Contains the test error message
  */
  public $message = null;

  function __construct($data = array()){
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['resultID'])) $this->resultID = (int) $data['resultID'];
    if (isset( $data['result'])) $this->result = (boolean) $data['result'];
    if (isset( $data['message'])) $this->message =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['message'] );
  }

  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function createEntry( $data = array() ) {
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['resultID'])) $this->resultID = (int) $data['resultID'];
    if (isset( $data['result'])) $this->result = (boolean) $data['result'];
    if (isset( $data['message'])) $this->message =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['message'] );
    $this -> insert();
  }

  /**
  * Returns all tests with a matching test run id
  *
  * @param int Optional The number of rows to return (default=all)
  * @param int Which run set to return
  * @return Array|false A two-element array : results => array, a list of Article objects; totalRows => Total number of articles
  */

  public static function getAssertSet( $resultID ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM asserts WHERE resultID = :resultID";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":resultID", $resultID, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $assert = new Assert( $row );
      $list[] = $assert;
    }

    // Now get the total number of articles that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Inserts assert results into the database, and sets its ID property.
  */

  public function insert() {

    // Does the Article object already have an ID?
    if ( !is_null( $this->id ) ){
      trigger_error ( "Article::insert(): Attempt to insert an Article that
          object already has its ID property set (to $this->id).", E_USER_ERROR );
    }

    // Insert the Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO asserts ( resultID, result, message )
            VALUES ( :resultID, :result, :message )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":resultID", $this->resultID, PDO::PARAM_INT );
    $st->bindValue( ":result", $this->result, PDO::PARAM_BOOL );
    $st->bindValue( ":message", $this->message, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }

}

?>
