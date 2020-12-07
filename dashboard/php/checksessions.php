<?php
    include_once "./db.php";

    if(isset($_SESSION['email']) and isset($_SESSION['userType'])){
        $email = $_SESSION['email']; $userType = $_SESSION['userType'];
        $sql = "SELECT `id` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }else{
        echo "false";
    }