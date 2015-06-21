<?php 
$search = $_POST['query_string'];


    // Connecting, selecting database
        include("../../secure/database.php");
        $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD)
	        or die('Could not connect: ' . pg_last_error());
        echo"<hr></hr>";
        if($conn) {
          echo "Successfully connected to DB";
        } else {
          echo "Failed to connect to DB";
        }

        // Free resultset
        pg_free_result($result);
        // Closing connection
        pg_close($conn);




?>