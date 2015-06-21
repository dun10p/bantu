<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$getFormData = $_GET;
$key = $getFormData[key];
$table = $getFormData[table];
$action = $getFormData[action];
$key_field = $getFormData[key_field];
$key_array = array($key, $table, $action, $key_field);
$query_param[];
$query = "UPDATE $table SET ";        

$j = 1; //counter used to populate pg_prepare query 
        // parameter placeholders and pg_excute parameter array
$arrayLength = count($_GET);       
$i = 1; //counter to create appropriate syntax in query
foreach ($getFormData as $field_name => $field_value){
    if ($field_value != NULL){
        if (!in_array($field_value, $key_array)){
            
            if (is_numeric($field_value)){
                $query = $query." ".$field_name." = $".$j;
                $query_param[$j-1] = htmlspecialchars($field_value);
                $j++;
            } else {
                $query = $query." ".$field_name." = $".$j;
                $query_param[$j-1] = htmlspecialchars($field_value);
                $j++;
            }
            if ($i < $arrayLength ){
                $query = $query.", ";
            } else {
                $query = $query." ";
            }
        }
    }
    $i++;
}
if (is_numeric($key)){
    $query = $query."WHERE $key_field = $key;";
} else {
    $query = $query."WHERE $key_field = '$key';";
}
include 'query.php';
echo "Edit was successful<br/>\n";	
echo "Return to <a href=\"index.php\">search page</a><br/>\n";
include 'closeConnection.php';

