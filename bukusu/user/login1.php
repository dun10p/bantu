<?php
//start output buffer to hold any html output until the location is verified as https
	ob_start();
?>

<?php
//verify the location is https - if not, redirect the user to the safe site and quit the script
	if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/user/login1.php');
		exit;
	}

//flush out the output buffer
	ob_flush();
//include("../insertTest.php");
//include database and connection files
	include("../secure/dbConnect.php");

//start a new session or continue an existing one
	session_start();
	if(!isset($_POST['submit'])){
     //   echo'Isset die<br>';
		die;
    }
	else if($_SESSION['logged']){
		header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');
       // echo'session logged<br>';
		exit;
        
	}

//clean the username with htmlspecialchars to avoid SQL injection attacks or XSS
	$cleanUN = htmlspecialchars($_POST['username']);

//prepare a query to grab the hashed password and salt associated with the typed in username
	pg_prepare($dbconn, 'auth-query', 'SELECT salt, passwordhash FROM gp.user_info WHERE userid = $1');
	$result = pg_execute($dbconn, 'auth-query', array($cleanUN));
if(!$result){
	echo "UhOH!";
	die;
}
	$row = pg_fetch_assoc($result);
//get rid of the space in salt - no idea why it is there!
	$rowSalt = str_replace(' ', '', $row['salt']);
//hash the user-typed password and the salt retrieved for the user from the database using SHA1
	$localhash = sha1($rowSalt . htmlspecialchars($_POST['password']));
//compare the two hashes, if they are equivalent, the user can be redirected to home.php, if not the user must try again
	if($localhash != $row['passwordhash']){
        echo "User Name:".$_POST['username']."<br>";
		echo "Please Enter Your Login Information Again<br>";
        echo "The local hash:". $localhash."<br>";
        echo "DB hash:".$row['passwordhash']."<br>";
        echo "The rowSalt:".$rowSalt."<br>";
        echo "The row salt:".$row['salt']."<br>";
		die;
	}
	else{
		//echo "YAY!<br>";
		//die;
	}
//Set the logged in key and the authorized key to true
	$_SESSION['logged'] = true;
	$_SESSION['authorized'] = true;
//Set the username key to the cleaned username
	$_SESSION['username'] = $cleanUN;	
//Set the action key to login in order for proper insertion into the database
	$_SESSION['action'] = 'login';
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	
//redirect the user to the home page once a successful login has been completed
	header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');
    //echo"Redirect you to Home page<br>";
	
//close the connection to the database
	pg_close($dbconn);
?>
