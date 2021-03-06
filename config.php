<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Australia/Sydney" );  // http://www.php.net/manual/en/timezones.php
define( "DB_DSN", "mysql:host=localhost;dbname=results" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "password" );
define( "CLASS_PATH", "classes" );
define( "TEST_PATH", "../web-animations-test-framework/tests");

require( CLASS_PATH . "/Result.php" );
require( CLASS_PATH . "/Assert.php" );
require( CLASS_PATH . "/Run.php" );
require( CLASS_PATH . "/QueuedRun.php" );

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}
?>
