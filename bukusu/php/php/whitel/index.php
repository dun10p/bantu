<!--Lizzy White
	EJWZC4
	Lab 8
	TA: Brooke
	4/8/2015
	https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/index.php-->
<?php
//start output buffer to hold any html output until the location is verified as https
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
			<p>Please Login
			<form action = "/~ejwzc4/cs3380/lab8/index.php" method='post'>
				<label for="username">Username:</label>
				<input type="text" name="username" id="username">
				<br>
				<br>
				<label for="password">Password:</label>
				<input type="password" name="password" id="password">
				<br>
				</hr>
				<br>
				<input type="submit" name="submit" value="Submit">
			</form>
			<p>Register <a href="registration.php">here</a></p>
			</p>
			</div>
	</div>
	</form>
<?php
//verify the location is https - if not, redirect the user to the safe site and quit the script
	if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/index.php');
		exit;
	}

//flush out the output buffer
	ob_flush();
include("../insertTest.php");
echo $query;
//include database and connection files
	include("../../secure/database.php");
	include("../../secure/connection.php");

//start a new session or continue an existing one
	session_start();
	if(!isset($_POST['submit']))
		die;
	else if($_SESSION['logged']){
		header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/home.php');
		exit;
	}

//clean the username with htmlspecialchars to avoid SQL injection attacks or XSS
	$cleanUN = htmlspecialchars($_POST['username']);

//prepare a query to grab the hashed password and salt associated with the typed in username
	pg_prepare($conn, 'auth-query', 'SELECT salt, password_hash FROM lab8.authentication WHERE username = $1');
	$result = pg_execute($conn, 'auth-query', array($cleanUN));

	$row = pg_fetch_assoc($result);
//get rid of the space in salt - no idea why it is there!
	$rowSalt = str_replace(' ', '', $row['salt']);
//hash the user-typed password and the salt retrieved for the user from the database using SHA1
	$localhash = sha1($rowSalt . htmlspecialchars($_POST['password']));
//compare the two hashes, if they are equivalent, the user can be redirected to home.php, if not the user must try again
	if($localhash != $row['password_hash']){
		echo "Please Enter Your Login Information Again";
		die;
	}

//Set the logged in key and the authorized key to true
	$_SESSION['logged'] = true;
	$_SESSION['authorized'] = true;
//Set the username key to the cleaned username
	$_SESSION['username'] = $cleanUN;	
//Set the action key to login in order for proper insertion into the database
	$_SESSION['action'] = 'login';
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	
	pg_prepare($conn, 'insert-query', 'INSERT INTO lab8.log (username, ip_address, log_date, action) VALUES ($1, $2, CURRENT_TIMESTAMP, $4)');
	$result = pg_execute($conn, 'insert-query', array($cleanUN, $_SESSION['ip'], $_SESSION['action']));

//redirect the user to the home page once a successful login has been completed
	header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/home.php');
	
//close the connection to the database
	pg_close($conn);
?>
</body>
</html>
