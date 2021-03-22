<?php

    include_once "./db.php";

    $username = $email = $password = "";
    $flag = 0;
    
    $response = array();
    $response['success'] = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

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
                $result = mysqli_query($conn, "SELECT `id` FROM `user` WHERE `email` = '$email' AND (`userType` <> 'google' AND `userType` <> 'facebook')"); //diff for google login
                if(mysqli_num_rows($result) <= 0){
                    $response['emailErr'] = "Oops, we cannot trace you, check email again!";
                    $flag = 1;
                }else if(mysqli_num_rows($result) > 1){
                    $response['emailErr'] = "Something went wrong at server side. Contact admin.";
                    $flag = 1;
                }
            }
        }

        // ==================================================
        // if no error enter in db
        // ==================================================
        if($flag == 0){
        
            // fetch data into db
            $sql = "SELECT * FROM `user` WHERE `email` = '$email'";
            $result = mysqli_query($conn, $sql);
            if($result){

                //write success code here
                $row = mysqli_fetch_assoc($result);
                $token = $row['token'];
                // sending email
                include_once "./actions/sendemail.php";
                $subject = "Reset your password";
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
                            <p class="mb-5">Click the button below to reset password of your BoomPanda Account</p>
                            <a class="btn" href="https://www.boompanda.in/login/php/actions/reset-password.php?email='.$email.'&token='.$token.'">Reset Password</a>
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