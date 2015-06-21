<?php

/* 
 * THIS IS EXAMPLE ON DYNAMICALLY GENERATING A QUERY STRING
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//include '../secure/database.php';
include '../secure/dbConnect.php';
//include '../demo/updatedExec.php';
                

foreach ($getFormData as $key => $value){
    echo "KEY --> $key   VALUE --> $value</br>";
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
 
    //Determine if "WHERE" clause is needed 
    foreach($getFormData as $field_name => $field_value){
	if ($field_value != NULL){
		$query = $query." WHERE";
		break;
	}
    }	
    $j = 1;
    
    $whereconstraint = null;

    $pquery;
    $rquery = null;
    $wfquery = null;
    $rwfquery = null;
    $query_param = array();
    //Add form data to $query string
    foreach ($getFormData as $field_name => $field_value){
        if ($field_value != NULL){
            //Determine which form field belongs to which db table
            switch ($field_name){
                case ('root'):   
                    if ($field_value != null){
                        $rquery = " a.".$field_name." ilike $ ".$j;
                        $query_param[$j-1] = "%".htmlspecialchars($field_value)."%";
                        $j++;  
                    }
                    break;
                case ('wordforms'):
                    if ($field_value != null){
                        $wfquery = " b.".$field_name." ilike $".$j;
                        $query_param[$j-1] = "%".htmlspecialchars($field_value)."%";
                        $j++;
                    }
                    break;
                case ('gloss'):
                    if ($field_value != null){
                        $gquery = " b.".$field_name." ilike $".$j;
                        $query_param[$j-1] = "%".htmlspecialchars($field_value)."%";
                        $j++;

                        $whereconstraint = updatewhere ($whereconstraint, $gquery);
                    }
                    break;
                case ('pos'):
                case ('subpos'):
                case ('numOfSyllables'):
                case ('tonalPattern'):
                    if ($field_value != null){
                        $wordstblquery = " b.".$field_name." = $".$j;
                        $query_param[$j-1] = $field_value;
                        $j++;
                   
                        $whereconstraint = updatewhere ($whereconstraint, $wordstblquery);
                    }
                    break;
                case ('modifier1')://modifiers may have mulitple
                case ('modifier2')://values and will be stored in
                    
                    $alen = count($field_value);
                    if ($field_value == null){
                        break;
                    }
                 
                                   
                    $d = 1;
                    $c = $j;
                    foreach ($field_value as $key => $value) {

                        if ($value != null){

                            $pquery = $pquery." c.".$field_name." = $".$c;
                            $query_param[$c-1] = $value;
                        }
echo "<br>  $field_name COUNTER -->> $d/$alen<br>";                        
                        if ($d < $alen ){
                            $pquery = $pquery." OR ";

                        } else {
                            $pquery = $pquery." ";
                        }
                        $c++;
                        $d++;
                        $j++;
                        
                    }
 
                    break; 
                case ('initialsound'):
                    if ($field_value != null){
                        $iquery = " a.".$field_name." = $".$j;
                        $query_param[$j-1] = $field_value;
                        $j++;

                        $whereconstraint = updatewhere ($whereconstraint, $iquery);
                    }
                    break;
                    //this field resides in root and word, need
                    //to pull the array for values
            }
            
echo "INCREMENT COUNTER -->>  $i /$arrayLength<br>";
            //Determine if another where constraint needs to be added
            
            
            
            if ($field_name == 'modifier1' || $field_name == 'modifier2'){
                if ($i < $arrayLength ){
                    $pquery = $pquery." OR ";

                } else {
                    $pquery = $pquery." ";
                }
            }
        }
        $i++;
    }
    
    if ($rquery != null && $wfquery != null){
        $rwfquery = " (".$rquery." or ".$wfquery.") ";
        echo "rwfQuery 1: $rwfquery";
    } elseif ($rquery != null){
        $rwfquery = $rquery;
        echo "rwfQuery 2: $rwfquery";
    } elseif ($wfquery != null){
        $rwfquery = $wfquery;
        echo "rwfQuery 3: $rwfquery";
    }
    if ($rwfquery != null){
         $whereconstraint = updatewhere ($whereconstraint, $rwfquery);
    }
     if ($pquery != null){
        $pquery = " (".$pquery.") ";
        echo "<hr><br>pquery != null: $pquery";
        $whereconstraint = updatewhere ($whereconstraint, $pquery);
    }
    
 
   
 
    
 
    
    /*foreach ($whereconstraint as $value) {
       echo "<br> Where constraint value => $value"; 
    }*/    
    $query = buildquery($whereconstraint, $query);
}
//build query

//display query
//echo "<p>QUERY: $query<p>";


//Run the query
//if no search criteria to add to query run query through pg_query
if ($getFormData == null){
    $result = pg_query($query);
}

else {
    

//if search criteria added via where, run query through pg_prepare and pg_execute

echo"<hr>";
    foreach ($query_param as $key => $value) {
echo "Query Param: KEY --> $key   Value --> $value<br>";
    }
//send $query and $query_param[] to:
   $result = pg_prepare($dbconn,"query", $query) 
        or 
        die("<br/>PG_PREPARE -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");
    $result = pg_execute($dbconn,"query", $query_param) 
        or 
        die("<br/>PG_EXEC -> Query failed: ".pg_last_error()."<br/>"
        . "Return to <a href=\"index.php\">search page</a><br/>\n");
$query = null;

}  


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
                
    pg_free_result($result);
    pg_close($dbconn);

function updatewhere ($whereconstraint, $updateQuery){
echo "<hr><br>Update where constraint array --> ".count($whereconstraint)."<br>";
    if ($whereconstraint == null) {
        $whereconstraint[0] = $updateQuery;
    }else if ($updateQuery != null){
        array_push($whereconstraint, $updateQuery);
    }
       
    return $whereconstraint;
}

function buildquery ($whereconstraint, $query){
   
    $len = count($whereconstraint);
    for ($i = 0; $i < $len; $i++){
echo "<br>BUILD ARRAY COUNTER --> $i/$len == $value<br>";
        if ($i < $len - 1){
echo "<br>if loop : i/len - 1 == $i/".$len-1;
            $query = $query.$whereconstraint[$i]. " AND ";    
        } else {
            
echo "<br> build for loop else: i = $i";
            $query = $query.$whereconstraint[$i];
                    
        }
      
    }
    return $query;
}
 ?>           