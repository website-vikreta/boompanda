<?php
    
    include_once "./db.php";

    // getting session variables
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_SESSION['userType']) && ($_SESSION['userType'] == 'google' || $_SESSION['userType'] == 'facebook')){
            echo "true";
        }else{
            echo "false";
        }
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['changePassword']) && $_POST['changePassword'] == true)){
        $response = array();
        $response['success'] = false;

        $cpassword = $npassword = $rpassword = "";
        $flag = 0;

        // current password
        if(empty($_POST['cpassword'])){
            $response['cpasswordErr'] = "Enter password";
            $flag = 1;
        }else{
            $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
            // check password in db
            $sql = "SELECT `password` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            if(!password_verify($cpassword, $row['password'])){
                $response['cpasswordErr'] = "Incorrect password";
                $flag = 1;
            }
        }

        // create password
        if(empty($_POST['npassword'])){
            $response['npasswordErr'] = "Enter password";
            $flag = 1;
        }else{
            $npassword = mysqli_real_escape_string($conn, $_POST['npassword']);
            // atleast 1 uppercase, 1 lower case, 1 digit  & 6 char long
            if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $npassword) === 0){
                $response['npasswordErr'] = "Password must contain atleast one uppercase, one lowercase, one digit and should be atleast 6 character long";
                $flag = 1;
            }
        }

        // reenter password password
        if(empty($_POST['rpassword'])){
            $response['rpasswordErr'] = "Enter password";
            $flag = 1;
        }else{
            $rpassword = mysqli_real_escape_string($conn, $_POST['rpassword']);
            // atleast 1 uppercase, 1 lower case, 1 digit  & 6 char long
            if($npassword == $rpassword){
                $npassword = password_hash($npassword, PASSWORD_DEFAULT);
            }else{
                $response['rpasswordErr'] = "Password doesn't match!";
                $flag = 0;
            }
        }

        // success update
        if($flag == 0){

            $sql = "UPDATE `user` SET `password` = '$npassword' WHERE `email` = '$email' AND `userType` = '$userType'";
            $result = mysqli_query($conn, $sql);
            if($result){
                $response['success'] = true;
            }

        }

        // output
        echo json_encode($response);
    }

    // changing email

    if($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['changeEmail']) && $_POST['changeEmail'] == true)){
        $response = array();
        $response['success'] = false;

        $newemail = "";
        $flag = 0;

        // email validations
        if(empty($_POST['email'])){
            $response['emailErr'] = "Required!";
            $flag = 1;
        }else{
            $newemail = mysqli_real_escape_string($conn, $_POST['email']);
            if(!filter_var($newemail, FILTER_VALIDATE_EMAIL)){
                $response['emailErr'] = "Invalid email address";
                $flag = 1;
            }else{
                // check whether it is present in db or not
                $result = mysqli_query($conn, "SELECT `id` FROM `user` WHERE `email` = '$newemail' AND (`userType` != 'google' AND `userType` != 'facebook')"); //diff for google login
                if(mysqli_num_rows($result) > 0){
                    $response['emailErr'] = "Oops, email is already taken!";
                    $flag = 1;
                }
            }
        }

        // success update
        if($flag == 0){

            $sql = "UPDATE `user` SET `email` = '$newemail', `status` = 'not verified' WHERE `email` = '$email' AND `userType` = '$userType'";
            $sql1 = "UPDATE `user_info` SET `email` = '$newemail' WHERE `email` = '$email' AND `userType` = '$userType'";
            $result = mysqli_query($conn, $sql);
            $result1 = mysqli_query($conn, $sql1);
            if($result AND $result1){

                $sql = "SELECT `token` FROM `user` WHERE `email` = '$newemail' AND `userType` = '$userType'";
                $res = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($res);
                $token = $row['token'];
                include_once "./actions/sendemail.php";
                $subject = "Verify your account";
                $body = "
                    <a href='localhost/boompanda/login/php/actions/verify.php?email=".$newemail."&token=".$token."'>Click here to verify</a>
                ";
                // $body = "
                //     <a href='https://www.boompanda.in/login/php/actions/verify.php?email=".$email."&token=".$token."'>Click here to verify</a>
                // ";
                $emailsend = sendEmail($newemail, $subject, $body);

                if($emailsend){
                    $_SESSION['email'] = $newemail;
                    $response['success'] = true;
                }
                
            }

        }
        // output
        echo json_encode($response);
    }


    // delet account
    if($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['deleteAccount']) && $_POST['deleteAccount'] == true)){

        $response = array();
        $response['success'] = false;

        $newemail = "";
        $flag = 0;

        $sql = "DELETE FROM `user` WHERE `email`= '$email' AND `userType` = '$userType'";
        $sql1 = "DELETE FROM `user_info` WHERE `email`= '$email' AND `userType` = '$userType'";
        $result = mysqli_query($conn, $sql);
        $result1 = mysqli_query($conn, $sql1);
        if($result and $result1){
            $response['success'] = true;
        }

        // output
        echo json_encode($response);
    }