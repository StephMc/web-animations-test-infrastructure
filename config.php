<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Australia/Sydney" );  // http://www.php.net/manual/en/timezones.php
define( "DB_DSN", "mysql:host=localhost;dbname=results" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "password" );
//define( "CLASS_PATH", "classes" );
//define( "TEMPLATE_PATH", "templates" );
//define( "HOMEPAGE_NUM_ARTICLES", 5 );
//define( "ADMIN_USERNAME", "root" );
//define( "ADMIN_PASSWORD", "password" );
//require( CLASS_PATH . "/Article.php" );

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}

// Display any errors on the screen
//set_exception_handler( 'handleException' );
?>