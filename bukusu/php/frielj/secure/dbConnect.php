<?php
    include "database.php";
    $dbconn = pg_connect("host=$dbhost dbname=$dbname password=$dbpass user=$dbuser")
        or die("Could not connect: " . pg_last_error()."<br>\nhost = $dbhost<br>\ndbname = $dbname<br>\npassword = $dbpass<br>\nuser = $dbuser"
    );
?>
