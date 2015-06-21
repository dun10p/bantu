<!--Lizzy White
	EJWZC4
	Lab 8
	TA: Brooke
	4/8/2015
	https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/home.php-->
<?php
//start output buffer to hold html in case of redirect to secure https
	ob_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset=UTF-8>
	<title>CS 3380 Lab 8</title>
</head>

<body>
	<div align = "center">
		<div id = "login">
<?php

//if the user doesn't go to a secure page, redirect them and quit
	if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/home.php');
		exit;
	}

//flush the output buffer to produce html
	ob_flush();
//start the session
	session_start();

//if the user is not looged in, redirect them to the login page and quit
	if(!$_SESSION['logged']){
		header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/index.php');
		exit;
	}

//establish connection to the database
	include("../../secure/database.php");
	include("../../secure/connection.php");

//grab the registration date and description for the user
	pg_prepare($conn, 'regDate-query', 'SELECT registration_date, description FROM lab8.user_info WHERE username = $1');
	$result = pg_execute($conn, 'regDate-query', array($_SESSION['username']));

	$row = pg_fetch_assoc($result);
	$regDate = $row['registration_date'];
	$description = $row['description'];

//print the table of data
	echo "\n\t<p>Username: " . $_SESSION['username'] . "</p>";
	echo "\n\t<p>IP Address: " . $_SESSION['ip'] . "</p>";
	echo "\n\t<p>Registration Date: " . $regDate . "</p>";
	echo "\n\t<p>Description: " . $description . "</p>";
	
//grab the user log info
	pg_prepare($conn, 'table-query', 'SELECT action, ip_address, log_date FROM lab8.log WHERE username = $1');
	$result = pg_execute($conn, 'table-query', array($_SESSION['username']));

//print the table
	include("../../secure/table.php");

//close the database connection
	pg_close($conn);
?>
	<p><a href="update.php">Click</a> to update page.</p>
	<p><a href="logout.php">Click here to logout</a></p>
</body>
</html>
