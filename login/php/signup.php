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
                $body = "
                    <h4>Hurray! you have successfully signup to boompanda's student community.</h4><p> One more step to login, kindly click below link to very yourself</p>
                    <a href='https://boompanda.in/login/php/actions/verify.php?email=".$email."&token=".$token."'>Click here to verify</a>
                ";
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