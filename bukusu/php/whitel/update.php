<!--Lizzy White
	EJWZC4
	Lab 8
	TA: Brooke
	4/8/2015
	https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/update.php-->
<?php
//start the output buffer to hold html
	ob_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset=UTF-8>
	<title>CS 3380 Lab 8</title>
</head>

<body>
<?php
//redirect the user if they did not access the secure https site
	if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/update.php');
		exit;
	}

//start the session, making sure the user is actually logged in.  If not, redirect them to the login page
	session_start();
	if(!$_SESSION['logged']){
		header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/index.php');
		exit;
	}

//flush the output buffer to produce the html
	ob_flush();

//establish secure connection to the database
	include("../../secure/database.php");
	include("../../secure/connection.php");

//grab the current description from the database	
	pg_prepare($conn, 'description-query', 'SELECT description FROM lab8.user_info WHERE username = $1');
	$result = pg_execute($conn, 'description-query', array($_SESSION['username']));
	$row = pg_fetch_assoc($result);
	
	echo "\n\t<form method='POST' action='/~ejwzc4/cs3380/lab8/update.php'>\n";
	echo "\t\t<div align='center'>\n";
	echo "\t\t\t<p>Username: " . $_SESSION['username'] . "</p>\n";
	echo "\t\t\t<table border='1'>\n";
	echo "\t\t\t\t<tr>\n";
	echo "\t\t\t\t\t<td><strong>Description</strong></td>\n";
	echo "\t\t\t\t\t<td><input type='text' name='description' value='" . $row['description'] . "'/></td>\n";
	echo "\t\t\t\t</tr>\n";
	echo "\t\t\t</table>\n";
	echo "\t\t<input type='submit' name='update' value='Save' /><p><a href='logout.php'>Click here to logout</a></p>\n";
	echo "\t\t</div>\n";
	echo "\t</form>\n";
echo $row['description'];

//don't do anything until the submit button has been pressed	
	if(!isset($_POST['update'])){
		die;
	}

	$description = htmlspecialchars($_POST['description']);
	$_SESSION['action'] = 'Updated description';
	
//update the user info in the database to reflect the newly typed in description	
	pg_prepare($conn, 'update-query', 'UPDATE lab8.user_info SET description = $1 WHERE username = $2');
	$result = pg_execute($conn, 'update-query', array($description, $_SESSION['username']));

//insert the log information into the database	
	pg_prepare($conn, 'log-query', 'INSERT INTO lab8.log (username, ip_address, log_date, action) VALUES ($1, $2, CURRENT_TIMESTAMP, $3)');
	$result = pg_execute($conn, 'log-query', array($_SESSION['username'], $_SESSION['ip'], $_SESSION['action']));

//redirect the user to the home page	
	header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/home.php');
//close the database connection
	pg_close($conn);
?>
</body>
</html>
