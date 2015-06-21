<!--
LAB 5
CS 3380
Jeffrey Friel (frielj)

Opens the database connection.
-->
<?php
include '../secure/dbConnect.php';

$stat = pg_connection_status($dbconn);
  if ($stat === PGSQL_CONNECTION_OK) {
      echo 'Connection status ok<br>';
  } else {
      echo 'Connection status bad<br>';
  } 
  
  
$result = pg_prepare($dbconn,"", $query) 
        or 
        die("<br/>PG_PREPARE -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");
$result = pg_execute($dbconn,"", $query_param) 
        or 
        die("<br/>PG_EXEC -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");

$query = "DEALLOCATE [PREPARE] query";
/*$deallocatePrepare = pg_query($query) 
        or 
        die("<br/>PG_EXEC -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");
*/
?>
