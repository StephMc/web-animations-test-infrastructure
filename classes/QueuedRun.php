<?php
class QueuedRun
{
  public $id = null;
  public $sha1 = null;
  public $commitMessage = null;
  public $commitURL = null;
  public $commitTime = null;

  function __construct($data = array()){
    if (isset( $data['id'])) $this->id = (int) $data['id'];
    if (isset( $data['sha1'])) $this->sha1 =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['sha1'] );
    if (isset( $data['commitMessage'])) $this->commitMessage =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitMessage'] );
    if (isset( $data['commitURL'])) $this->commitURL =
      preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitURL'] );
    if ( isset( $data['commitTime'] ) ) $this->commitTime = $data['commitTime'];
  }

  public function createEntry( $data = array() ) {
    if (isset( $data['sha1'])) $this->sha1 =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['sha1'] );
    if (isset( $data['commitMessage'])) $this->commitMessage =
        preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitMessage'] );
    if (isset( $data['commitURL'])) $this->commitURL =
      preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['commitURL'] );
    if ( isset( $data['commitTime'] ) ) $this->commitTime = $data['commitTime'];
    $this -> insert();
    return $this -> id;
  }

  public function delete() {
    if ( is_null( $this->id ) ) trigger_error ( "QueuedRun::delete(): Attempt to delete an QueuedRun object that does not have its ID property set.", E_USER_ERROR );

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM queuedRun WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

  public static function getNextRun() {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM queuedRun ORDER BY commitTime DESC LIMIT 0, 1 ";

    $st = $conn->prepare( $sql );
    $st->execute();
    $list = array();

    $row = $st->fetch();
    $run = new QueuedRun( $row );

    $conn = null;
    return ( $run );
  }

  public function insert() {
    if ( !is_null( $this->id ) ){
      trigger_error ( "QueuedRun::insert(): Attempt to insert an QueuedRun that
          object already has its ID property set (to $this->id).", E_USER_ERROR );
    }

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    // change it to take the time later
    $sql = "INSERT INTO queuedRun ( sha1, commitMessage, commitURL, commitTime )
            VALUES ( :sha1, :commitMessage, :commitURL,  date('Y-m-d H:i:s') )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":sha1", $this->sha1, PDO::PARAM_STR );
    $st->bindValue( ":commitMessage", $this->commitMessage, PDO::PARAM_STR );
    $st->bindValue( ":commitURL", $this->commitURL, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }
}
?>
