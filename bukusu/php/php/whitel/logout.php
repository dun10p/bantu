<!--Lizzy White
	EJWZC4
	Lab 8
	TA: Brooke
	4/8/2015
	https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/logout.php-->
<?php
//star the session
	session_start();
//establsih a connection to the database
	include("../../secure/database.php");
	include("../../secure/connection.php");	
	$_SESSION['action'] = 'logout';
//insert the information into the user log
	pg_prepare($conn, 'logout-query', 'INSERT INTO lab8.log (username, ip_address, log_date, action) VALUES ($1, $2, CURRENT_TIMESTAMP, $3)');
	$result = pg_execute($conn, 'logout-query', array($_SESSION['username'], $_SESSION['ip'], $_SESSION['action']));
	$_SESSION['logged'] = false;	
//unset and destroy the session
	session_unset();
	session_destroy();
//close the connection to the database
	pg_close($conn);	
//redirect the user to the login screen
	header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/index.php');
?>
