<?php
    include_once "./db.php";

    if(isset($_SESSION['email']) and isset($_SESSION['userType'])){
        $email = $_SESSION['email']; $userType = $_SESSION['userType'];
        $sql = "SELECT `id` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
        $result = mysqli_query($conn, $sql);
        if($result){
            $userType1 = $_GET['type'];
            if($userType1 == $userType OR $userType1 == 'all'){
                echo "true";
            }else if(($userType == 'google' OR $userType == 'facebook') AND $userType1 == 'boompanda'){
                echo "true";
            }else if($userType == 'superadmin' AND $userType1 == 'admin'){
                echo "true";
            }else{
                echo "false2";
            }
        }else{
            echo "false";
        }
    }else{
        echo "false";
    }