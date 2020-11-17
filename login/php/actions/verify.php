<?php
    session_start();
    if(!empty($_GET['email']) && !empty($_GET['token'])){
        $_SESSION['email'] = $_GET['email'];
        $_SESSION['token'] = $_GET['token'];
        header('location: ../../verifyuser.php');
    }
?>