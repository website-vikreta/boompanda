<?php

session_start();
//logout.php
echo $_SESSION['userType'];
if($_SESSION['userType'] == 'google'){
    require_once "./config.php";
    //Reset OAuth access token
    $accesstoken=$_SESSION['access_token'];
    $google_client = new Google_Client();
    $google_client->revokeToken(['refresh_token' => $accesstoken]);
}

//Destroy entire session data.
$helper = array_keys($_SESSION);
foreach ($helper as $key){
    unset($_SESSION[$key]);
}
session_destroy();

//redirect page to index.php
header('location: ../index.html');

?>