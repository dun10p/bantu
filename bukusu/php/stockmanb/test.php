<?php
//$dbsalt = (string)$row['salt'];
//$ppw = (string)htmlspecialchars($_POST['password']);


$dbsalt = "378339570";
$ppw = "1234";
$localhash = sha1( $dbsalt.$ppw);
echo "local hash: ".$localhash;
echo "\n";
echo "DB salt: ".$dbsalt;
echo "\n";
echo "Post PW: ".$ppw.".";
?>