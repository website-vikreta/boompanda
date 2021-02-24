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
                $result = mysqli_query($conn, "SELECT `id` FROM `user` WHERE `email` = '$email' AND (`userType` <> 'google' OR `userType` <> 'facebook')"); //diff for google login
                if(mysqli_num_rows($result) <= 0){
                    $response['emailErr'] = "Oops, we cannot trace you, check email again!";
                    $flag = 1;
                }else if(mysqli_num_rows($result) > 1){
                    $response['emailErr'] = "Something went wrong at server side. Contact admin";
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
                $body = "
                    <a href='https://www.boompanda.in/login/php/actions/reset-password.php?email=".$email."&token=".$token."'>Click here to reset password</a>
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