<?php 
    
    session_start();
    $ssl = $_SERVER['HTTPS'];
    $host = $_SERVER['HTTP_HOST'];
    
    // Check if user is logged in
    if (!(isset($_SESSION["User"])&& $_SESSION["User"]!='')){
        $url = '/~bskt3/cs3380/lab8/index.php';
        header("Location: https://$host$url");
        exit; 
    }
    else{
        $url = '/~bskt3/cs3380/lab8/home.php';
    } 
    //Check if https is used?
    //If not build url and redirect to ssl connection to this page
    if($ssl != 'on'){
        header("Location: https://$host$url");
        exit;  
    }
?>