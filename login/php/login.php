<?php
    include_once "./db.php";

    $username = $password = "";
    $flag = 0;
    $response = array();
    $array['success'] = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        // ==================================================
        // username validations
        // ==================================================
        if(empty($_POST['username'])){
            $response['usernameErr'] = "Enter username";
            $flag = 1;
        }else{
            $username = mysqli_real_escape_string($conn, $_POST['username']);
        }

        // ==================================================
        // password validations
        // ==================================================
        if(empty($_POST['password'])){
            $response['passwordErr'] = "Enter password";
            $flag = 1;
        }else{
            $password = mysqli_real_escape_string($conn, $_POST['password']);
        }

        // ==================================================
        // if no error check in db
        // ==================================================
        if($flag == 0){
            $sql = "SELECT * FROM `user` WHERE (`username` = '$username' OR `email` = '$username') AND (`userType` <> 'google' AND `userType` <> 'facebook')";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_assoc($result);
                if(password_verify($password, $row['password'])){

                    //write success code here
                    if($row['status'] == "verified"){
                        $response['success'] = true;
                        $sql = "SELECT * FROM `user_info` WHERE `email` = '$username' AND `userType` = '".$row['userType']."'";
                        $r = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                        if((($r['mobile_number'] == "") || ($r['gender'] == "") || ($r['dob'] == "") || ($r['college_name'] == "") || ($r['state'] == "") || ($r['interests'] == "") || ($r['year'] == 0)) && ($r['userType'] == 'boompanda' || $r['userType'] == 'google')){
                            $_SESSION['update_profile'] = true;
                        }
                        if($r['cookies'] == 0){
                            $_SESSION['cookies'] = true;
                        }
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['userType'] = $row['userType'];
                    }else{
                        $response['serverErr'] = "Verify your account first, click on link we sent you to your email";
                    }
                    //write success code here
                    
                }else{
                    $response['serverErr'] = "Invalid email or password!";
                }
            }else if(mysqli_num_rows($result) < 1){
                $response['serverErr'] = "Invalid email or password1!";
            }else{
                $response['serverErr'] = "Please contact admins. We encounter some issue.";
            }
        }
        
        echo json_encode($response);
    }
?>