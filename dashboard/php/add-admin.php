<?php

    include_once "./db.php";

    $name = $email = $password = $cpassword = $oldpass = "";
    $flag = 0;

    $response = array();
    $response['success'] = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        // ==================================================
        // name validations
        // ==================================================
        if(empty($_POST['name'])){
            $response['nameErr'] = "Required!";
            $flag = 1;
        }else{
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            if(preg_match("/[^A-Za-z '-]/", $name)){
                $response['nameErr'] = "Invalid name entered";
                $flag = 1;
            }
        }

        // ==================================================
        // email validations
        // ==================================================
        if(empty($_POST['email'])){
            $response['emailErr'] = "Required!";
            $flag = 1;
        }else{
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $response['emailErr'] = "Invalid email address";
                $flag = 1;
            }else{
                // check whether it is present in db or not
                $result = mysqli_query($conn, "SELECT `id` FROM `user` WHERE `email` = '$email'"); //diff for google login
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
            $response['passwordErr'] = "Required!";
            $flag = 1;
        }else{
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            // atleast 1 uppercase, 1 lower case, 1 digit  & 6 char long
            if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password) === 0){
                $response['passwordErr'] = "Password must contain atleast one uppercase, one lowercase, one digit and should be atleast 6 character long";
                $flag = 1;
            }
            $oldpass = $password;
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
            
            
            // insert data into db
            $sql = "INSERT INTO `user`(`name`,`password`, `email`, `userType`, `status`, `token`) VALUES ('$name', '$password', '$email', 'admin', 'not verified', '$token')";;
            $result = mysqli_query($conn, $sql);
            if($result){

                //write success code here
                // sending email
                include_once "./actions/sendemail.php";
                $subject = "Verify your account as admin";
                $body = "
                    <p>Username: <b>".$email."</b></p>
                    <p>Password: <b>".$oldpass."</b></p>
                    <a href='localhost/boompanda/login/php/actions/verify.php?email=".$email."&token=".$token."'>Click here to verify</a>
                ";
                $emailsend = sendEmail($email, $subject, $body);
                if($emailsend)
                    $response['success'] = true;
                
                //write success code here
            }else
                $response['success'] = false;

        }
        

        echo json_encode($response);
    }