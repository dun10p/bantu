<?php
//require_once 'login.php';
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/html; charset=UTF-8");
//start output buffer to hold html in case of redirect to secure https
	ob_start();

?>


<?php
//if the user doesn't go to a secure page, redirect them and quit
/*if(!isset($_SERVER['HTTPS'])){
		header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');
		exit;
	}*/
 
//flush the output buffer to produce html
	ob_flush();
//start the session

	session_start();
$User = "LoggedIn";
//http://www.w3schools.com/json/json_example.asp 
   
//if the user is not looged in, redirect them to the login page and quit
	if(!$_SESSION['logged']){
		//header('Location: https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');
		//exit;
        $User = "LoggedOut";
	}
    else {
        $User = "LoggedIn";
    }





//print the table of data
	//echo "\n\t<p>Username: " . $_SESSION['username'] . "</p>";
	//echo "\n\t<p>IP Address: " . $_SESSION['ip'] . "</p>";
	//echo "\n\t<p>Registration Date: " . $regDate . "</p>";
	//echo "\n\t<p>Description: " . $description . "</p>";
	




 //   $User = $_SESSION["User"];
    switch ($User){
        case "LoggedIn":
            $result = '[{"Link" : "https://babbage.cs.missouri.edu/~cs3380s15grp15/insert.php", "Name" : "Insert"}, '.
            '{"Link" : "https://babbage.cs.missouri.edu/~cs3380s15grp15/user/registration.html", "Name" : "Add User"}, '.
            '{"Link" : "https://babbage.cs.missouri.edu/~cs3380s15grp15/user/LogOut.html", "Name" : "Log-Out"}]';
            break;
        case "LoggedOut":
            $result ='[{"Link" : "https://babbage.cs.missouri.edu/~cs3380s15grp15/user/LogIn.html", "Name" : "Log-In"}]';
            break;
    }
    echo $result;
?>

