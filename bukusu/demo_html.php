<?php
header("Access-Control-Allow-Origin: *");
 header("Content-Type: text/html; charset=UTF-8");
?>

<?php
        // Connecting, selecting database
        include("../../secure/database.php");
        $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD)
	        or die('Could not connect: ' . pg_last_error());
        if($conn) {
          //echo "<p>Successfully connected to DB</p>";
        } else {
          //echo "<p>Failed to connect to DB</p>";
        }
        $query = "SELECT * FROM noun";
        if($query != "NULL"){
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            
            //Return the number of rows returned
            $rows = pg_num_rows($result);
            //echo "There were <em>".$rows."</em> rows returned <br/><br/>";
        }
        else
        {
            //echo "There were <em> 0 </em> rows returned <br/><br/>";
        }
        // Printing results in HTML
        echo '<table>';
        echo "\n";
        
        $i = pg_num_fields($result);
        for($j = 0; $j < $i; $j++){
            $fieldname = pg_field_name($result, $j);
            echo'<td><b>';
            echo $fieldname;
            echo '</b></td>';
            echo "\n";
        }
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t";
            echo '<tr>';
            echo "\n";
            foreach ($line as $col_value) {
                echo "\t\t";
                echo'<td>';
                echo $col_value;
                echo '</td>';
                echo "\n";
            }
            echo "\t";
            echo '</tr>';
            echo "\n";
        }
        echo '</table>';
        echo "\n";

        // Free resultset
        pg_free_result($result);

        // Closing connection
        pg_close($conn);
    ?>