<?php

/* 
 * THIS IS EXAMPLE ON DYNAMICALLY GENERATING A QUERY STRING
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//include '../secure/database.php';
include '../secure/dbConnect.php';


//Checking status of connection, these lines will be removed
$stat = pg_connection_status($dbconn);
  if ($stat === PGSQL_CONNECTION_OK) {
      echo 'Connection status ok<br>';
  } else {
      echo 'Connection status bad<br>';
  } 

//Test Session array section -- remove this 
//$_GET = null;
//$_GET['tonalPattern'] = 'High on Noun Class Prefix';
//$_SESSION['gloss'] = 'dog';
echo var_dump($_SESSION);

//place get data in variable to be used in logic
$getFormData = $_SESSION;

/*QUERY LOGIC FOR SEARCH PAGE QUERY*/

$query = "SELECT a.root as Root, b.wordform as Word, b.tonalPattern as Tone,b.gloss as WordDef, b.wordID as Wordid, c.phraseform as Phrase, c.definition as PhraseDef, d.url as Audio FROM gp.root AS a 
        LEFT OUTER JOIN gp.words AS b ON a.rootid = b.rootid 
        LEFT OUTER JOIN gp.phrasal_data AS c ON (
            b.wordid = c.noun1 OR 
            b.wordid = c.noun2 OR 
            b.wordid = c.noun3 OR 
            b.wordid = c.verb1 OR 
            b.wordid = c.verb2 OR 
            b.wordid = c.verb3) 
		LEFT OUTER JOIN gp.audio AS d ON b.audioid = d.audioid ";        

$j = 1; //counter used to populate pg_prepare query 
        // parameter placeholders and pg_excute parameter array

$arrayLength = count($getFormData);    //get length of form array that holds search values

$i = 1; //counter to create appropriate syntax in query
 if (!empty($getFormData)){
 
    //Determine if "WHERE" clause is needed 
    foreach($getFormData as $field_name => $field_value){
	if ($field_value != NULL){
		$query = $query." WHERE ";
		break;
	}
    }	
 
    //Add form data to $query string
    foreach ($getFormData as $field_name => $field_value){
        if ($field_value != NULL){
            //Determine which form field belongs to which db table
            switch ($field_name){
                case ('root'):                    
                    $query = $query." a.";
                    break;
                case ('gloss'):
                case ('wordforms'):
                case ('pos'):
                case ('subpos'):
                case ('numOfSyllables'):
                case ('tonalPattern'):
                    $query = $query." b.";
                    break;
                case ('modifier1')://modifiers may have mulitple
                case ('modifier2')://values and will be stored in 
                                   //array in post array
                    $query = $query." c.";
                    break;
                case ('initialsound'):
                    //this field resides in root and word, need
                    //to pull the array for values
            }
            //Format field name for pg_prepare and add field_value to query_param
            //array to pass through pg_execute
            if (is_numeric($field_value)){
                $query = $query.$field_name." = $".$j;                
                $query_param[$j-1] = htmlspecialchars($field_value);
                $j++;
            } else {
                //Format field name of searchable text fields with with wild cards
                if (strtoupper($field_name) == 'WORDFORM' || 
                        strtoupper($field_name) == 'ROOT' || 
                        strtoupper($field_name) == 'GLOSS'
                    ){
                    $query = $query.$field_name." ilike $".$j."";
                    $query_param[$j-1] = "%".htmlspecialchars($field_value)."%";
                } else {
                    $query = $query.$field_name." = $".$j;
                    $query_param[$j-1] = htmlspecialchars($field_value);
                }
                
                $j++;
            }
            
            //Determine if another where constraint needs to be added
            if ($i < $arrayLength ){
                $query = $query." AND ";

            } else {
                $query = $query." ";
            }
        }
        $i++;
    }
}
//display query
echo "QUERY: $query";
//Run the query
//if no search criteria to add to query run query through pg_query
if ($getFormData == null){
    $result = pg_query($query);
}

//if search criteria added via where, run query through pg_prepare and pg_execute
else {
//send $query and $query_param[] to:
   $result = pg_prepare($dbconn,"query", $query) 
        or 
        die("<br/>PG_PREPARE -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");
    $result = pg_execute($dbconn,"query", $query_param) 
        or 
        die("<br/>PG_EXEC -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");

}  


//Print of returned data -- T
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
                   
                    foreach($line as $key => $col_value){
						if ($key == 'audio' && $col_value != null) {?><td>
					<input type=button value="Popup Player" onClick="javascript: popUp('popup-player.html','<?php
					echo $col_value
					?>')"></td><?php
						}
						else {
							echo "\t\t\t\t<td>$col_value</td>\n"; 
						}
                    }
                    echo "\t\t\t</tr>\n";
                }
                echo "\t\t</table>\n";
//*************************************************
                
    pg_free_result($result);
    pg_close($dbconn);


 ?>           