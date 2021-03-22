<?php

    include_once "./db.php";

    $username = $email = $password = "";
    $flag = 0;
    
    $response = array();
    $response['success'] = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // ==================================================
        // username validations
        // ==================================================
        if(empty($_POST['username'])){
            $response['usernameErr'] = "Enter username";
            $flag = 1;
        }else{
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            // check whether username contains only alpha numeric chars or nor
            $allowed = array("_");
            if(!ctype_alnum (str_replace($allowed, '', $username))){
                $response['usernameErr'] = "Username must contain letters, numbers & underscores only";
                $flag = 1;
            }else{
                // check whether it is present in db or not
                $result = mysqli_query($conn, "SELECT `id` FROM `user` WHERE `username` = '$username'"); //diff for google login
                if(mysqli_num_rows($result) > 0){
                    $response['usernameErr'] = "Oops, username is already taken!";
                    $flag = 1;
                }
            }
        }

        // ==================================================
        // email validations
        // ==================================================
        if(empty($_POST['email'])){
            $response['emailErr'] = "Enter email address";
            $flag = 1;
        }else{
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $response['emailErr'] = "Invalid email address";
                $flag = 1;
            }else{
                // check whether it is present in db or not
                $result = mysqli_query($conn, "SELECT `id` FROM `user` WHERE `email` = '$email' AND (`userType` = 'boompanda' OR `userType` = 'admin' OR `userType` = 'superadmin')"); //diff for google login
                if(mysqli_num_rows($result) > 0){
                    $response['emailErr'] = "Oops, email is already taken!";
                    $flag = 1;
                }
            }
        }

        // ==================================================
        // password validations
        // ==================================================
        if(empty($_POST['password'])){
            $response['passwordErr'] = "Enter password";
            $flag = 1;
        }else{
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            // atleast 1 uppercase, 1 lower case, 1 digit  & 6 char long
            if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password) === 0){
                $response['passwordErr'] = "Password must contain atleast one uppercase, one lowercase, one digit and should be atleast 6 character long";
                $flag = 1;
            }
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        // ==================================================
        // if no error enter in db
        // ==================================================
        if($flag == 0){

            // generating token
            $token = openssl_random_pseudo_bytes(32); //random string
            $token = bin2hex($token); //convert binary into hexadecimal
            $token=time()."".$token;

            // generating unique number
            $uid = 0;
            while(1){
                $uid = mt_rand(100000, 999999)                ;
                $sql = "SELECT * FROM `user_info` WHERE `uid` = '$uid'";
                if(mysqli_num_rows(mysqli_query($conn, $sql)))
                    continue;
                else
                    break;
            }
            
            
            // insert data into db
            $d = date("Y-m-d");
            $sql = "INSERT INTO `user`(`username`, `password`, `email`, `userType`, `date`, `status`, `token`) VALUES ('$username', '$password', '$email', 'boompanda', '$d', 'not verified', '$token')";;
            $result = mysqli_query($conn, $sql);
            $sql1 = "INSERT INTO `user_info`(`email`, `userType`, `uid`) VALUES ('$email', 'boompanda', '$uid')";
            $result1 = mysqli_query($conn, $sql1);
            $sql2 = "INSERT INTO `wallet`(`email`, `userType`) VALUES ('$email', 'boompanda')";
            $result2 = mysqli_query($conn, $sql2);
            if($result && $result1 && $result2){

                //write success code here
                // sending email
                include_once "./actions/sendemail.php";
                $subject = "Verify your account";
                $body = '
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <link rel="preconnect" href="https://fonts.gstatic.com">
                        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"> 
                        <style>
                            *{
                                margin: 0;
                                padding: 0;
                            }
                            .wrapper{
                                max-width: 600px;
                                margin:auto;
                                font-family: "Poppins", sans-serif;;
                                padding: 0.5rem 1rem;
                            }
                            .wrapper a{
                                text-decoration:none;
                                color: #ea1821;
                            }
                            .wrapper img{
                                margin-top: 1rem;
                            }
                            .wrapper hr{
                                border-color: #ddd;
                                margin: 1rem 0;
                            }
                            .wrapper h1{
                                font-weight: bold;
                                text-transform: capitalize;
                                color: #333;
                                margin-top: 1.5rem;
                                font-size: 1.5rem;
                            }
                            .wrapper p{
                                color: #555;
                                font-size: 0.9rem;
                                margin: 0.25rem 0;
                            }
                            .wrapper .dark{
                                background: #222;
                                padding: 2rem;
                                margin-top: 2rem;
                            }
                            .wrapper .dark p{
                                color: #ddd;
                                font-size: 0.8rem;
                                margin:  0 0;
                                text-align: center;
                            }
                            .wrapper .btn{
                                padding: 0.6rem 1.5rem;
                                border:none;
                                background: #ea1821;
                                color: white;
                                font-weight:bold;
                                border-radius:3px;
                            }
                            .wrapper .mb-5{
                                margin-bottom:2rem !important;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="wrapper">
                            <img src="http://www.boompanda.in/assets/logo.png" class="img-fluid" alt="" style="max-width:200px">
                            <hr>
                            <h1>Hey there,</h1>
                            <p>Thanks for Signing Up.</p>
                            <p>Just one more step to get started with a fun-filled journey...</p>
                            <p class="mb-5">Click the button below to activate your BoomPanda Account</p>
                            <a class="btn" href="https://boompanda.in/login/php/actions/verify.php?email='.$email.'&token='.$token.'">Verify Now</a>
                            <div class="dark">
                                <p>
                                    Copyright © 2021 BoomPanda (Gladius Ventures LLP), All rights reserved.
                                </p>
                                <p>
                                    If you don\'t recognize this mail, you can write to team@boompanda.in 
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>
                ';
                $emailsend = sendEmail($email, $subject, $body);
                if($emailsend)
                    $response['success'] = true;
                
                //write success code here
            }else
                $response['success'] = false;

        }

        // printing json object
        echo json_encode($response);
    }

?>