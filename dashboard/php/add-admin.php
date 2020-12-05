<?php

    include_once "./db.php";

    $name = $email = $password = $cpassword = $oldpass = $state = $city = $language = "";
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
        // state validations
        // ==================================================
        if($_POST['state'] == ""){
            $response['stateErr'] = "Required!";
            $response['cityErr'] = "Required!";
            $flag = 1;
        }else{
            $state = mysqli_real_escape_string($conn, $_POST['state']);
            // remove blank spaces
            $state = str_replace(" ", "", $state);
        }

        // ==================================================
        // city validations
        // ==================================================
        if($_POST['city'] == ""){
            $response['cityErr'] = "Required!";
            $flag = 1;
        }else{
            $city = mysqli_real_escape_string($conn, $_POST['city']);
            // remove blank spaces
            $city = str_replace(" ", "", $city);
        }

        // ==================================================
        // language validations
        // ==================================================
        if(empty($_POST['language'])){
            $response['languageErr'] = "Required!";
            $flag = 1;
        }else{
            $language = mysqli_real_escape_string($conn, $_POST['language']);
            if(preg_match("/[^A-Za-z'-]/", $language)){
                $response['languageErr'] = "Invalid language entered";
                $flag = 1;
            }
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
            $sql1 = "INSERT INTO `user_info`(`email`, `userType`, `city`, `state`, `language`) VALUES ('$email', 'admin', '$city', '$state', '$language')";
            $result = mysqli_query($conn, $sql);
            $result1 = mysqli_query($conn, $sql1);
            if($result && $result1){
                $response['success'] = true;
                //write success code here
            }else
                $response['success'] = false;

        }
        

        echo json_encode($response);
    }