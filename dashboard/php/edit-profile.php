<?php

    include_once "./db.php";

    // getting session variables
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $sql = "SELECT `user`.*, `user_info`.*
                FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` 
                WHERE `user`.`email` = '$email' AND `user`.`userType` = '$userType' AND `user_info`.`userType` = '$userType'";
        $result=mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        $row['success'] = true;
        echo json_encode($row);
    }

    // ===============================================
    // for edit student profile
    // ===============================================
    if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['user'] == 'student'){
        $response = array();
        $response['success'] = false;
        $name = $username = $mobile = $gender = $dob = $state = $city = $permanant_address = $current_address = $bio = "";
        $stay = $referral = $interest = $college = $course = $college_name = $year = "";
        $flag = 0;

        // name validation
        if(!empty($_POST['name'])){
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            if(preg_match("/[^A-Za-z '-]/", $name)){
                $response['nameErr'] = "Invalid name entered";
                $flag = 1;
            }
        }

        // username validation
        if(!empty($_POST['username'])){
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $allowed = array("_");
            if(!ctype_alnum (str_replace($allowed, '', $username))){
                $response['usernameErr'] = "Username contains only letters & numbers";
                $flag = 1;
            }else{
                // select all row except current users
                $sql = "SELECT `id` FROM `user` WHERE `username` = '$username' 
                EXCEPT SELECT `id` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
                $result = mysqli_query($conn,$sql);
                if(mysqli_num_rows($result) > 0){
                    $response['usernameErr'] = "Username already taken";
                    $flag = 1;
                }
            }
        }

        // mobile validation
        if(!empty($_POST['mobile'])){
            $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
            if(!preg_match('/^[0-9]{10}+$/', $mobile)){
                $response['mobileErr'] = "Invalid mobile number";
                $flag = 1;
            }
        }

        // gender
        if(!empty($_POST['gender'])){
            $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        }

        // dob
        if(!empty($_POST['dob'])){
            $dob = mysqli_real_escape_string($conn, $_POST['dob']);
            $y = explode("-",$dob);
            if((date('Y') - $y[0]) < 16){
                $response['dobErr'] = "You must atleast 16years old to proceed.";
                $flag = 1;
            }
        }

        // college
        if($_POST['college'] != "-1+-1"){
            $college = mysqli_real_escape_string($conn, $_POST['college']);
        }
        if(!empty($_POST['college_name'])){
            $college_name = mysqli_real_escape_string($conn, $_POST['college_name']);
            $college_name = $college_name == '-- Select College --' ? "" : $college_name;
        }
        if($_POST['course'] != "-1"){
            $course = mysqli_real_escape_string($conn, $_POST['course']);
        }
        if($_POST['year'] != "0"){
            $year = mysqli_real_escape_string($conn, $_POST['year']);
        }

        // state & city
        if($_POST['state'] != ""){
            $state = mysqli_real_escape_string($conn, $_POST['state']);
            $state = str_replace(" ", "", $state);
            if($_POST['city'] != ""){
                $city = mysqli_real_escape_string($conn, $_POST['city']);
                $city = str_replace(" ", "", $city);    
            }else{
                $response['cityErr'] = "City is required!";
                $flag = 1;
            }
        }

        // parmanant address
        if(!empty($_POST['parmanant_address'])){
            $permanant_address = mysqli_real_escape_string($conn, $_POST['parmanant_address']);
        }

        // current address
        if(!empty($_POST['current_address'])){
            $current_address = mysqli_real_escape_string($conn, $_POST['current_address']);
        }

        // bio
        if(!empty($_POST['bio'])){
            $bio = mysqli_real_escape_string($conn, $_POST['bio']);
        }

        // stay
        if(!empty($_POST['stay'])){
            $stay = mysqli_real_escape_string($conn, $_POST['stay']);
        }

        // referral
        if(!empty($_POST['referral'])){
            $referral = mysqli_real_escape_string($conn, $_POST['referral']);
            $referral = strtoupper($referral);
            if(!preg_match('/^[a-zA-Z0-9]{8}+$/', $referral)){
                $response['referralErr'] = "Invalid referral";
                $flag = 1;
            }
        }

        // interest
        $interest_list = json_decode($_POST['interests']);
        if(sizeof($interest_list) > 0){
            if(sizeof($interest_list) != 5){
                $response['interestErr'] = "Choose exactly 5 interest.";
                $flag = 1;
            }else{
                $interest = mysqli_real_escape_string($conn, implode(',', $interest_list));
            }
        }

        // profile image
        // validating display picture
        $location = "";  $uploadOk = 1;

        if(isset($_FILES['profile']['name'])){
            /* Getting file name */
            $filename = $_FILES['profile']['name'];
            $location = "../media/profiles/".$filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $location = "../media/profiles/".round(microtime(true)).".".$imageFileType;
            

            /* Valid Extensions */
            $valid_extensions = array("jpg","jpeg","png");

            /* Check file extension */
            if(!in_array(strtolower($imageFileType),$valid_extensions) ) {
                $uploadOk = 0;
                $response['profileErr'] = "Unable to upload file. Invalid image format";
                $flag = 1;
            }
        }

        // on success do update
        if($flag == 0){
            // copy file to directory
            if($location != ""){
                // unlink previous file
                $filecheck ="SELECT `profile` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
                $filecheckres = mysqli_query($conn, $filecheck);
                $filerow = mysqli_fetch_assoc($filecheckres);
                if($filerow['profile'] != ""){
                    if (substr($filerow['profile'], 0, 4) != "http") {
                        unlink($filerow['profile']);
                    }
                }
                // move new file to the location
                compress_image($_FILES['profile']['tmp_name'], $location, 50);
                // move_uploaded_file($_FILES['profile']['tmp_name'],$location);
            }
            // launch Update query
            $sql = "UPDATE `user_info` SET `mobile_number`='$mobile',`gender`='$gender',`dob`='$dob',`college` = '$college', `college_name` = '$college_name', `course` = '$course', `year` = '$year', `state`='$state',`city`='$city', `permanant_address`='$permanant_address',`current_address`='$current_address',`interests`='$interest',`stay`='$stay',`bio`='$bio',`referral`='$referral' WHERE `email` = '$email' AND `userType` = '$userType' ";
            $result = mysqli_query($conn, $sql);
            if($location != ""){
                $sql1 = "UPDATE `user` SET `username` = '$username', `profile` = '$location', `name` = '$name' WHERE `email` = '$email' AND `userType` = '$userType'";
            }else{
                $sql1 = "UPDATE `user` SET `username` = '$username', `name` = '$name' WHERE `email` = '$email' AND `userType` = '$userType'";
            }
            $result1 = mysqli_query($conn, $sql1);
            if($result && $result1){
                $response['success'] = true;
            }

        }

        // returning JSON output
        echo json_encode($response);
    }

    // ===============================================
    // for edit admin profile
    // ===============================================
    if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['user'] == 'admin'){
        $response = array();
        $response['success'] = false;
        $name = $username = $mobile = $gender = $dob = $state = $city = "";
        $flag = 0;

        // name validation
        if(!empty($_POST['name'])){
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            if(preg_match("/[^A-Za-z '-]/", $name)){
                $response['nameErr'] = "Invalid name entered";
                $flag = 1;
            }
        }

        // username validation
        if(!empty($_POST['username'])){
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            if(preg_match("/[^A-Za-z0-9'-]/", $username)){
                $response['usernameErr'] = "Username contains only letters & numbers";
                $flag = 1;
            }else{
                // select all row except current users
                $sql = "SELECT `id` FROM `user` WHERE `username` = '$username' 
                EXCEPT SELECT `id` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
                $result = mysqli_query($conn,$sql);
                if(mysqli_num_rows($result) > 0){
                    $response['usernameErr'] = "Username already taken";
                    $flag = 1;
                }
            }
        }

        // mobile validation
        if(!empty($_POST['mobile'])){
            $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
            if(!preg_match('/^[0-9]{10}+$/', $mobile)){
                $response['mobileErr'] = "Invalid mobile number";
                $flag = 1;
            }
        }

        // gender
        if(!empty($_POST['gender'])){
            $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        }

        // dob
        if(!empty($_POST['dob'])){
            $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        }

        // state & city
        if($_POST['state'] != ""){
            $state = mysqli_real_escape_string($conn, $_POST['state']);
            $state = str_replace(" ", "", $state);
            if($_POST['city'] != ""){
                $city = mysqli_real_escape_string($conn, $_POST['city']);
                $city = str_replace(" ", "", $city);    
            }else{
                $response['cityErr'] = "City is required!";
                $flag = 1;
            }
        }

        // profile image
        // validating display picture
        $location = "";  $uploadOk = 1;

        if(isset($_FILES['profile']['name'])){
            /* Getting file name */
            $filename = $_FILES['profile']['name'];
            $location = "../media/profiles/".$filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $location = "../media/profiles/".round(microtime(true)).".".$imageFileType;
            

            /* Valid Extensions */
            $valid_extensions = array("jpg","jpeg","png");

            /* Check file extension */
            if(!in_array(strtolower($imageFileType),$valid_extensions) ) {
                $uploadOk = 0;
                $response['profileErr'] = "Unable to upload file. Invalid image format";
                $flag = 1;
            }
        }

        // on success do update
        if($flag == 0){
            // copy file to directory
            if($location != ""){
                // unlink previous file
                $filecheck ="SELECT `profile` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
                $filecheckres = mysqli_query($conn, $filecheck);
                $filerow = mysqli_fetch_assoc($filecheckres);
                if($filerow['profile'] != ""){
                    if (substr($filerow['profile'], 0, 4) != "http") {
                        unlink($filerow['profile']);
                    }
                }
                // move new file to the location
                compress_image($_FILES['profile']['tmp_name'], $location, 50);
                // move_uploaded_file($_FILES['profile']['tmp_name'],$location);
            }
            // launch Update query
            $sql = "UPDATE `user_info` SET `mobile_number`='$mobile',`gender`='$gender',`dob`='$dob',`state`='$state',`city`='$city' WHERE `email` = '$email' AND `userType` = '$userType' ";
            $result = mysqli_query($conn, $sql);
            if($location != ""){
                $sql1 = "UPDATE `user` SET `username` = '$username', `profile` = '$location', `name` = '$name' WHERE `email` = '$email' AND `userType` = '$userType'";
            }else{
                $sql1 = "UPDATE `user` SET `username` = '$username', `name` = '$name' WHERE `email` = '$email' AND `userType` = '$userType'";
            }
            $result1 = mysqli_query($conn, $sql1);
            if($result && $result1){
                $response['success'] = true;
            }

        }


        // returning JSON output
        echo json_encode($response);

    }


    // image compression function
    function compress_image($source_url, $destination_url, $quality)
    {
        $info = getimagesize($source_url);
        if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
        elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
        elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
        imagejpeg($image, $destination_url, $quality);
        return true;
    }