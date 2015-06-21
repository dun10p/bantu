<?php
	include("../secure/dbConnect.php");
	$getFormData = $_POST;
	$table = "gp." . $getFormData['table'];

//	$table = "gp.root";
//	$getFormData = array(
//		"root" => "TEST5",
//		"rootLength" => 5,
//		"sigma1" => 2,
//		"sigma2" => 1
//	);
	$query = "INSERT INTO $table (";
	$j = 1;
	$arrayLength = count($getFormData);
	$i = 1;
//test:	foreach($key_array as $field_name => $field_value){	
	foreach($getFormData as $field_name => $field_value){
		if(!empty($field_value) && ($field_name != 'table')&&$field_name != 'submit'){
//			if(!in_array($field_value, $key_array)){
				$query = $query . $field_name;
                                
                                if ($field_name == 'root'){
                                    $idType = 'rootid';
									$table2 = 'root';
									$key = 'root';
									$val = htmlspecialchars($field_value);
                                }
								else if($field_name == 'wordForm'){
									$idType = 'wordid';
									$table2 = 'words';
									$key = 'wordform';
									$val = htmlspecialchars($field_value);
								}

				$query_param[$j-1] = htmlspecialchars($field_value);
				$j++;
//			}
			if($i < $arrayLength){
				$query = $query . ", ";
			}
			
				$i++;
		}

	}
//	else{
				$query = substr($query,0,-2);
				$query = $query . ") VALUES (";
//			}
				
	for($v = 1; $v < $j; $v++){
		$query = $query . "$" . $v;
		if($v != ($j-1)){
			$query = $query . ", ";
		}
		else{
			$query = $query . ")";
	}
	}	
//	$pQuery = pg_prepare($dbconn, "", $query) or die("<br/>PG_PREPARE -> Query failed: ".pg_last_error()."<br/>");
//	$result = pg_execute($dbconn, "", $query_param) or die("<br/>PG_EXEC -> Query failed: ".pg_last_error() . "<br/>");
	include 'query.php';
    if($result && $table == 'gp.root'){
		header("location:https://babbage.cs.missouri.edu/~cs3380s15grp15/demo/wordInsert.php");
	}
/*******************************************************
 * Grabbing the root id generated from the insert
 */        

//$query = "SELECT " . $idType . " FROM gp. " . $table2 . " WHERE " . $key . " = $1"; 
//$query = "SELECT rootID FROM gp.root WHERE root = $1";
/*$query_param = null;
$query_param[0] = $val;
echo "Query Param = $query_param[0]";
foreach ($query_param as $key => $value) {
    echo "<br>KEY --> $key   Value --> $value";
}*/


//include 'query.php';

//Test print of returned data -- This will be removed
//*******************************************
/*
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
   */             

    
        
        
        
//	pg_close($dbconn);
//	echo $query;
//	echo "\nValues: ". $query_param[0] . " and " . $query_param[1];
?>
