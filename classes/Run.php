<?php

/**
 * Class to handle articles
 */

class Run {
  // Properties

  /**
  * @var int The article ID from the database
  */
  public $id = null;

  /**
  * @var int When the article is to be / was first published
  */
  public $runTime = null;

  /**
  * @var string Full title of the article
  */
  public $commitSHA = null;


  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function createEntry( $data = array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['runTime'] ) ) $this->runTime = (int) $data['runTime'];
    if ( isset( $data['commitSHA'] ) ) $this->commitSHA =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitSHA'] );
    $this -> insert();
    return $this -> id;
  }

  /**
  * Returns all (or a range of) test runs
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the articles (default="runTime DESC")
  * @return Array|false A two-element array : results => array, a list of Article objects; totalRows => Total number of articles
  */

  public static function getList( $numRows=1000000, $order="runTime DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(runTime) AS runTime FROM results
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $article = new Article( $row );
      $list[] = $article;
    }

    // Now get the total number of articles that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Inserts the current testRun object into the database, and sets its ID property.
  */

  public function insert() {

    // Does the Article object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Insert the Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO runs ( runTime, commitSHA ) VALUES ( now(), :commitSHA )";
    $st = $conn->prepare ( $sql );
    //$st->bindValue( ":runTime", $this->runTime, PDO::PARAM_INT );
    $st->bindValue( ":commitSHA", $this->commitSHA, PDO::PARAM_INT );
    $st->execute();
    $this -> id = $conn->lastInsertId();
    $conn = null;
  }
}
?>
