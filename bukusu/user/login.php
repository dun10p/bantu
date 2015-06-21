<?php
//start output buffer to hold any html output until the location is verified as https
	//ob_start();
?>
<!DOCTYPE html>
<html>

<head>
   <title>Log-In</title>
   <link href="/~cs3380s15grp15/Site.css" rel="stylesheet">
   
</head>

<body>

    <nav id="nav01"></nav>
    <nav id="nav02"></nav>

	<div id="main">
        <h1>Log-In</h1>
        	<div align = "center">
		        <div id = "login">
			        <p>Please Login</p>
            
			        <form name="login" action="https://babbage.cs.missouri.edu/~cs3380s15grp15/user/login.php" method="post">
				        <label for="username">Username:</label>
				        <input type="text" name="username" id="username">
				        <br>
				        <br>
				        <label for="password">Password:</label>
				        <input type="password" name="password" id="password">
				        <br>
				        <br>
				        <input type="submit" name="submit" value="Submit" />
			        </form>
                
			
		        </div>
            </div>
        
        <!--div id="id01" -->
       
    
<?php
//verify the location is https - if not, redirect the user to the safe site and quit the script
	/*if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/user/login.php');
		exit;
	}*/

//flush out the output buffer
	//ob_flush();
//include("../insertTest.php");
//include database and connection files
	include("../secure/dbConnect.php");

//start a new session or continue an existing one
	session_start();
	if(!isset($_POST['submit'])){
        echo'Isset die<br>';
		die;
    }
	else if($_SESSION['logged']){
		//header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');
		//exit;
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
		echo "YAY!";
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
	
//redirect the user to the home page once a successful login has been completed
	//header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');

	
//close the connection to the database
	pg_close($dbconn);
?>
        
        <!--/div-->
    

</div>
<footer id="foot01"></footer>
<script src="/~cs3380s15grp15/Script.js"></script>
</body>
</html> 