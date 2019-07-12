<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
   $dbhost = 'localhost:3036';
   $dbuser = 'nkmtein_dev';
   $dbpass = "m9QxpUr?\$xRt";
   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
   
   if(! $conn ) {
      die('Could not connect: ' . mysql_error());
   }
   
   $sql = 'DROP DATABASE nkmtein_leads';
   $retval = mysql_query( $sql, $conn );
   
   if(! $retval ) {
      die('Could not delete database db_test: ' . mysql_error());
   }
   
   echo "Database deleted successfully\n";
   
   mysql_close($conn);
?>