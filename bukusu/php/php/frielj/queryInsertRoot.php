<?php

/**************************************************** 
 *INSERT INTO ROOT TABLE
 * 
 * ROOT TABLE DEFINITION
 *                                        Table "gp.root"
    Column    |         Type          |                       Modifiers
--------------+-----------------------+-------------------------------------------------------
 rootid       | integer               | not null default nextval('root_rootid_seq'::regclass)
 root         | character varying(40) | not null
 rootlength   | smallint              | not null
 initialsound | character varying(10) |
 sigma1       | smallint              | not null
 sigma2       | smallint              | not null
Indexes:
    "root_pkey" PRIMARY KEY, btree (rootid)
Foreign-key constraints:
    "root_initialsound_fkey" FOREIGN KEY (initialsound)
 *       REFERENCES initial_sound(initialsound) ON DELETE CASCADE
Referenced by:
    TABLE "words" CONSTRAINT "words_rootid_fkey" 
 *      FOREIGN KEY (rootid) REFERENCES root(rootid)
 
 */

  
//Test Session array section -- remove this 
//$_SESSION = null;
$_SESSION['root'] = 'INSERT TEST2';
$_SESSION['rootlength'] = 2;
//$_SESSION['initialsound'] = 'V';
$_SESSION['sigma1'] = 1;
$_SESSION['sigma2'] = 2;

//place get data in variable to be used in logic
$getFormData = $_SESSION;
/***********************************************************
 * ROOT TABLE INSERT QUERY 
 ***********************************************************/
$query = "INSERT INTO gp.root ($colums) VALUES ( $values)";        

$j = 1; //counter used to populate pg_prepare query 
        // parameter placeholders and pg_excute parameter array

$arrayLength = count($_SESSION); 
echo "Session length = $arrayLength";


$i = 1; //counter to create appropriate syntax in query

//Assign table columns to $columns and values to $values
foreach ($getFormData as $field_name => $field_value){
    if ($field_value != NULL){
        
                $columns = $columns.$field_name;
                if ($field_name == 'root'){
                    $rootTemp = $field_value;
                }
echo "<p>columns = $columns</p>";
                $values = $values."$".$j;
echo "<p>values = $values</p>";
                $query_param[$j-1] = htmlspecialchars($field_value);
                $j++;
            
            if ($i < $arrayLength ){
                $columns = $columns.", ";
                $values = $values.", ";
            } else {
                $columns = $columns." ";
                $values = $values." ";
            }
        }
    $i++;
}

$query = "INSERT INTO gp.root ($columns) VALUES ($values)"; 
echo "<p>Generated Query == $query";



include 'query.php';
foreach ($result as $key => $value) {
    echo "<br>KEY --> $key   Value --> $value";
}



include 'closeConnection.php';

$query = "SELECT rootID FROM gp.root WHERE root = $1";
$query_param = null;
$query_param[0] = $rootTemp;
echo "Query Param = $query_param[0]";
foreach ($query_param as $key => $value) {
    echo "<br>KEY --> $key   Value --> $value";
}


include 'query.php';

//Test print of returned data -- This will be removed
//*******************************************

    $numRows = pg_num_rows($result);
    echo "<p>There were  <em>$numRows</em> rows returned</p>\n";
                
                echo "\t\t<table>\n";
                echo "\t\t\t<tr>\n";
                for ($i=0; $i<pg_num_fields($result); $i++){
                    $col_heading = pg_field_name($result, $i);
                    echo "\t\t\t\t<th>$col_heading</th>\n";
                }
                echo "\t\t\t</tr>\n";
                
                while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
                    echo "\t\t\t<tr>\n";
                   
                    foreach($line as $col_value){                       
                        echo "\t\t\t\t<td>$col_value</td>\n"; 
                    }
                    echo "\t\t\t</tr>\n";
                }
                echo "\t\t</table>\n";
//*************************************************
                



include 'closeConnection.php';

echo "Edit was successful<br/>\n";	
echo "Return to <a href=\"index.php\">search page</a><br/>\n";


