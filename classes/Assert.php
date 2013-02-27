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

  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function __construct( $data = array() ) {
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['resultID'])) $this->resultID = (int) $data['resultID'];
    if (isset( $data['result'])) $this->result = (boolean) $data['result'];
    if (isset( $data['message'])) $this->message =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['message'] );
  }


  // /**
  // * Sets the object's properties using the edit form post values in the supplied array
  // *
  // * @param assoc The form post values
  // */

  // public function storeFormValues ( $params ) {

  //   // Store all the parameters
  //   $this->__construct( $params );

  //   // Parse and store the publication date
  //   if ( isset($params['publicationDate']) ) {
  //     $publicationDate = explode ( '-', $params['publicationDate'] );

  //     if ( count($publicationDate) == 3 ) {
  //       list ( $y, $m, $d ) = $publicationDate;
  //       $this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
  //     }
  //   }
  // }


  // /**
  // * Returns an Article object matching the given article ID
  // *
  // * @param int The article ID
  // * @return Article|false The article object, or false if the record was not found or there was a problem
  // */

  // public static function getById( $id ) {
  //   $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
  //   $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE id = :id";
  //   $st = $conn->prepare( $sql );
  //   $st->bindValue( ":id", $id, PDO::PARAM_INT );
  //   $st->execute();
  //   $row = $st->fetch();
  //   $conn = null;
  //   if ( $row ) return new Article( $row );
  // }


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
    $sql = "INSERT INTO results ( testRunID, testName )
            VALUES ( :testRunID, :testName )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":testRunID", $this->testRunID, PDO::PARAM_INT );
    $st->bindValue( ":testName", $this->testName, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  // /**
  // * Updates the current Article object in the database.
  // */

  // public function update() {

  //   // Does the Article object have an ID?
  //   if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );

  //   // Update the Article
  //   $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
  //   $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate), title=:title, summary=:summary, content=:content WHERE id = :id";
  //   $st = $conn->prepare ( $sql );
  //   $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
  //   $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
  //   $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
  //   $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
  //   $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
  //   $st->execute();
  //   $conn = null;
  // }


  // /**
  // * Deletes the current Article object from the database.
  // */

  // public function delete() {

  //   // Does the Article object have an ID?
  //   if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

  //   // Delete the Article
  //   $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
  //   $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
  //   $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
  //   $st->execute();
  //   $conn = null;
  // }

}

?>
