<?php 
    // session_start();

    // if ($_SESSION['loggedin'] !== 1) {
    //     // header('HTTP/1.1 401 Unauthorized'); 
    //     // header('WWW-Authenticate: Basic realm="Our Blog"'); 
    //     exit("Access Denied: Username and password required."); 
    // }
    if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    // else {
    //     header("location: index.php");
    //     exit;
    // }
    // define('ADMIN_LOGIN','wally'); 
    // define('ADMIN_PASSWORD','mypass'); 

    // if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) 
    //     || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN) 
    //     || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) { 
    // header('HTTP/1.1 401 Unauthorized'); 
    // header('WWW-Authenticate: Basic realm="Our Blog"'); 
    // exit("Access Denied: Username and password required."); 
    // } 
    
?>
