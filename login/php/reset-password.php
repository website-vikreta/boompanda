<?php

    include_once "./db.php";

    $password = $cpassword = "";
    $flag = 0;
    $email = $_SESSION['email'];
    $token = $_SESSION['token'];
    $response = array();
    $response['success'] = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        

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
        }

        // ==================================================
        // cpassword validations
        // ==================================================
        if(empty($_POST['cpassword'])){
            $response['cpasswordErr'] = "Enter password again";
            $flag = 1;
        }else{
            $cpassword = mysqli_real_escape_string($conn, $_POST['password']);
            // atleast 1 uppercase, 1 lower case, 1 digit  & 6 char long
            if($password != $cpassword){
                $response['cpasswordErr'] = "Password does not match. Check again";
                $flag = 1;
            }else{
                $password = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        // ==================================================
        // if no error enter in db
        // ==================================================
        if($flag == 0){            
            
            // insert data into db
            $sql = "UPDATE `user` SET `password`='$password' WHERE `email` = '$email' AND `token` = '$token'";
            $result = mysqli_query($conn, $sql);
            if($result){
                $response['success'] = true;
            }else
                $response['success'] = false;

        }

        // printing json object
        echo json_encode($response);
    }

?>