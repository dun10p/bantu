<!--Lizzy White
	EJWZC4
	Lab 8
	TA: Brooke
	4/8/2015
	https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/registration.php-->
<?php
//start the output buffer to hold any html in the event of a redirect
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
			<p>Please Register
			<form action = "/~ejwzc4/cs3380/lab8/registration.php" method='post'>
				<label for="username">Username:</label>
				<input type="text" name="username" id="username">
				<br>
				<br>
				<label for="password">Password:</label>
				<input type="password" name="password" id="password">
				<br>
				<br>
				<input type="submit" name="submit" value="Submit">
			</form>
			</p>
		</div>
	</div>
<?php
//if the user did not access the secure site, redirect them to the https
	if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/registration.php');
		exit;
	}

//flush out the output buffer to produce html page
	ob_flush();
	
//do nothing until the submit button has been pressed
	if(!isset($_POST['submit']))
		die;
	
//establish a connection to the database
	include("../../secure/database.php");
	include("../../secure/connection.php");

//start the session
	session_start();

	$cleanUN = htmlspecialchars($_POST['username']);

//check that username and password fields are not empty
	if($cleanUN == NULL){
		echo "Username field cannot be empty.";
		die;
	}
	else if($_POST['password'] == NULL){
		echo "Password field cannot be empty.";
		die;
	}
//insert the username into the database - checking that the query does not fail	and that it does not already exist
	pg_prepare($conn, 'userinfo-query', 'INSERT INTO lab8.user_info (username) VALUES($1)');
	$result = pg_execute($conn, 'userinfo-query', array($cleanUN));

	if(!$result){
		echo "Failed to Execute!\n" . pg_last_error();
		echo "\nReturn to <a href='registration.php'>registration page</a>";
		die;
	}


//seed the random number generator and create a random salt
	mt_srand();
	$salt = mt_rand();
	$clean_pw = htmlspecialchars($_POST['password']);

//hash the concatenation of the salt and the cleaned password
	$pwhash = sha1($salt . $clean_pw);

//set session variables
	$_SESSION['action'] = 'Register';
	$_SESSION['logged'] = true;
	$_SESSION['authorized'] = true;
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];	
	$_SESSION['username'] = $cleanUN;

//insert username, passwordhash, and salt into database
	pg_prepare($conn, 'insert-query', 'INSERT INTO lab8.authentication VALUES($1, $2, $3)');
	$result = pg_execute($conn, 'insert-query', array($cleanUN, $pwhash, $salt));

//log the user info
	pg_prepare($conn, 'log-query', 'INSERT INTO lab8.log (username, ip_address, log_date, action) VALUES($1, $2, CURRENT_TIMESTAMP, $3)');
	$result = pg_execute($conn, 'log-query', array($cleanUN, $_SESSION['ip'], $_SESSION['action']));

//close the connection to the database
	pg_close($conn);

//redirect the user to the home page
	header('Location: https://babbage.cs.missouri.edu/~ejwzc4/cs3380/lab8/home.php');
	
?>
</body>
</html>
