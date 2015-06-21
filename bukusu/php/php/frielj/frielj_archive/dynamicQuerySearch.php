<?php

/* 
 * THIS IS EXAMPLE ON DYNAMICALLY GENERATING A QUERY STRING
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include 'secure/database.php';
include 'secure/dbConnect.php';

$stat = pg_connection_status($dbconn);
  if ($stat === PGSQL_CONNECTION_OK) {
      echo 'Connection status ok';
  } else {
      echo 'Connection status bad';
  }   
$_SESSION[] = null;
//$_SESSION['tonalPattern'] = 'High';
//$getFormData = $_SESSION;//place get data in variable to be used in logic
$getFormData = $_SESSION;

foreach ($getFormData as $key => $value) {
    echo "Key -> $key   Value -> $value";
}
/*QUERY LOGIC FOR SEARCH PAGE QUERY*/

$query = "SELECT a.root as Root, b.wordform as Word, b.tonalPattern as Tone,b.gloss as WordDef, b.wordID as Wordid, c.phraseform as Phrase, c.definition as PhraseDef FROM gp.root AS a 
        LEFT OUTER JOIN gp.words AS b ON a.rootid = b.rootid 
        LEFT OUTER JOIN gp.phrasal_data AS c ON (
            b.wordid = c.noun1 OR 
            b.wordid = c.noun2 OR 
            b.wordid = c.noun3 OR 
            b.wordid = c.verb1 OR 
            b.wordid = c.verb2 OR 
            b.wordid = c.verb3)";        

$j = 1; //counter used to populate pg_prepare query 
        // parameter placeholders and pg_excute parameter array

$arrayLength = count($getFormData);    //get length of form array that holds search values

$i = 1; //counter to create appropriate syntax in query
 if (!empty($getFormData)){
     
    foreach($getFormData as $field_name => $field_value){
	if ($field_value != NULL){
		$query = $query." WHERE";
		break;
	}
    }	
    
    foreach ($getFormData as $field_name => $field_value){
        if ($field_value != NULL){
            //echo "TABLE = ".pg_field_table($field_value, $i-1);
            switch ($field_name){
                case ('root'):
                    $query = $query." a.";
                    break;
                case ('gloss'):
                case ('wordid'):
                    $query = $query." b.";
                    break;
                case ('PHRASAL_DATA'):
                    $query = $query." c.";
                    break;
            }

            if (is_numeric($field_value)){
                $query = $query.$field_name." = $".$j;
                $query_param[$j-1] = htmlspecialchars($field_value);
                $j++;
            } else {
                if (strtoupper($field_name) == 'WORDFORM' || 
                        strtoupper($field_name) == 'ROOT' || 
                        strtoupper($field_name) == 'GLOSS'
                    ){
                    $query = $query.$field_name." ilike $".$j."";
                    $query_param[$j-1] = htmlspecialchars($field_value);
                } else {
                    $query = $query.$field_name." = $".$j;
                    $query_param[$j-1] = htmlspecialchars($field_value);
                }
                
                $j++;
            }
            if ($i < $arrayLength ){
                $query = $query." AND ";

            } else {
                $query = $query." ";
            }
        }
        $i++;
    }
}


echo "<p> Query = $query</p>";
foreach ($getFormData as $key => $value) {
    echo "<br>Key -> $key   Value -> $value";
}

foreach ($query_param as $key => $value) {
    echo "<br>Key -> $key   Value -> $value";
}
 $result = pg_query($dbcnn, $query);
/*
//send $query and $query_param[] to:
   $result = pg_prepare($dbconn,"query", $query) 
        or 
        die("<br/>PG_PREPARE -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");
    $result = pg_execute($dbconn,"query", $query_param) 
        or 
        die("<br/>PG_EXEC -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");
*/
foreach ($result as $key => $value) {
    echo "<br>Key -> $key   Value -> $value";
}    
    
    $numRows = count($result);
    echo "<p>There were  <em>$numRows</em> rows returned</p>\n";
                
                echo "\t\t<table>\n";
                echo "\t\t\t<tr>\n";
                for ($i=0; $i<pg_num_fields($result); $i++){
                    $col_heading = pg_field_name($result, $i);
                    echo "\t\t\t\t<th>$col_heading</th>\n";
                }
                echo "\t\t\t</tr>\n";
                
                while ($line = pg_fetch_array($result, null, PGSQL_NUM)){
                    echo "\t\t\t<tr>\n";
                   
                    foreach($line as $col_value){                       
                        echo "\t\t\t\t<td>$col_value</td>\n"; 
                    }
                    echo "\t\t\t</tr>\n";
                }
                echo "\t\t</table>\n";
                
                pg_free_result($result);
                pg_close($dbconn);
session_close();
session_destroy();
 ?>           