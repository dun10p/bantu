<?php

if(!isset($_SESSION['username'])){
	header('Location:https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');
}
//start the output buffer to hold any html in the event of a redirect
	ob_start();
?>

<?php
//if the user did not access the secure site, redirect them to the https
	if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/user/registration.php');
		exit;
	}

//flush out the output buffer to produce html page
	ob_flush();
	
//do nothing until the submit button has been pressed
	if(!isset($_POST['submit']))
		die;
	
//establish a connection to the database
	include("../secure/dbConnect.php");

//start the session
	session_start();

	$cleanUN = htmlspecialchars($_POST['username']);
	$cleanFName = htmlspecialchars($_POST['fname']);
	$cleanLName = htmlspecialchars($_POST['lname']);
//check that username and password fields are not empty
	if($cleanUN == NULL){
		echo "Username field cannot be empty.";
		die;
	}
	else if($_POST['password'] == NULL){
		echo "Password field cannot be empty.";
		die;
	}
	else if($cleanFName == NULL){
		echo "What is your first name?";
		die;
	}
	else if($cleanLName == NULL){
		echo "What is your last name?";
		die;
	}
//insert the username into the database - checking that the query does not fail	and that it does not already exist
	pg_prepare($dbconn, 'userinfo-query', 'INSERT INTO gp.users VALUES($1, $2, $3)');
	$result = pg_execute($dbconn, 'userinfo-query', array($cleanUN, $cleanLName, $cleanFName));

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
	pg_prepare($dbconn, 'insert-query', 'INSERT INTO gp.user_info VALUES($1, $2, $3)');
	$result = pg_execute($dbconn, 'insert-query', array($cleanUN, $pwhash, $salt));

//log the user info
//	pg_prepare($conn, 'log-query', 'INSERT INTO lab8.log (username, ip_address, log_date, action) VALUES($1, $2, CURRENT_TIMESTAMP, $3)');
//	$result = pg_execute($conn, 'log-query', array($cleanUN, $_SESSION['ip'], $_SESSION['action']));

//close the connection to the database
	pg_close($dbconn);
echo "New user successfully created";
sleep(10);
echo '<script>window.location.assign("../Index.html")</script>';
//redirect the user to the home page
//	header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/search.html');
	
?>