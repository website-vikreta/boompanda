<?php

    include_once "./db.php";
    extract($_POST);

    // * ====================================
    // * ADD USER
    // * ====================================
    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['adduser']) && $_POST['adduser'] == true){
        $username = $email = $password = "";
        $flag = 0;
        
        $response = array();
        $response['success'] = false;

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
                $response['usernameErr'] = "Username must contain letters, underscore & numbers only";
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
            $tempPass = $password;
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
            $sql = "INSERT INTO `user`(`username`, `password`, `email`, `userType`, `status`, `token`) VALUES ('$username', '$password', '$email', 'boompanda', 'verified', '$token')";;
            $result = mysqli_query($conn, $sql);
            $sql1 = "INSERT INTO `user_info`(`email`, `userType`) VALUES ('$email', 'boompanda')";
            $result1 = mysqli_query($conn, $sql1);
            if($result && $result1){

                //write success code here
                // sending email
                include_once "./actions/sendemail.php";
                $subject = "Account credentials";
                $body = "
                    <b>Username: </b> ".$username."<br>
                    <b>Email: </b> ".$email."<br>
                    <b>Password: </b>".$tempPass."<br>
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

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){

        $data = "
            <div class='table-responsive'>
            <table class='table-striped' id='myTable' width='100%'>
                <thead>
                    <td><b>Sr No</b></td>
                    <td><b>Name</b></td>
                    <td><b>Email</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Course</b></td></td>
                    <td><b>Year</b></td></td>
                    <td><b>State</b></td></td>
                    <td><b>City</b></td></td>
                    <td><b>User Type</b></td></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td><b>Sr No</b></td>
                    <td><b>Name</b></td>
                    <td><b>Email</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Course</b></td></td>
                    <td><b>Year</b></td></td>
                    <td><b>State</b></td></td>
                    <td><b>City</b></td></td>
                    <td><b>User Type</b></td></td>
                    <td class='text-center'><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT `user`.id, `user`.`name`, `user`.`email`, `user`.`status`, `user`.`userType`, `user_info`.`state`, `user_info`.`city`, `user_info`.`college_name`, `user_info`.`course`, `user_info`.`year` 
                FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` AND `user`.`userType` = `user_info`.`userType`
                WHERE `user`.`userType` <> 'admin' AND `user`.`userType` <> 'superadmin'";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td>".$row['name']."</td>
                        <td>".$row['email']."</td>
                        <td>".$row['college_name']."</td>
                        <td>".$row['course']."</td>
                        <td class='text-center'>".$row['year']."</td>
                        <td>".$row['state']."</td>
                        <td>".$row['city']."</td>
                        <td>".$row['userType']."</td>
                        <td class='d-flex justify-content-end'>
                            <button class='btn solid rounded btn-info user-".$row['id']."' title='View complete info' id='view".$row['id']."' onclick='ViewUser(".$row['id'].")' data-toggle='modal' data-target='#view-user-modal'><i class='far fa-eye'></i></button>
                            <button class='btn solid rounded btn-warning user-".$row['id']."' title='Send message' data-toggle='modal' data-target='#send-message-modal'><i class='far fa-paper-plane'></i></button>
                ";
                if($row['status'] == "not verified"){
                    $data .= "
                    <button class='btn solid rounded btn-success user-".$row['id']."' id='approve".$row['id']."' onclick='ApproveUser(".$row['id'].")' data-toggle='tooltip' title='Verify'><i class='far fa-check'></i></button>
                    ";
                }else if($row['status'] == "verified"){
                    $data .= "
                    <button class='btn solid rounded btn-info user-".$row['id']."' id='disapprove".$row['id']."' onclick='DisapproveUser(".$row['id'].")' data-toggle='tooltip' title='Not Verify'><i class='far fa-times'></i></button>
                    ";
                }
                $data .= "                        
                        <button class='btn solid rounded btn-secondary user-".$row['id']."'><i class='far fa-edit'></i></button>
                        <button class='btn solid rounded btn-danger user-".$row['id']."' id='delete".$row['id']."' onclick='DeleteUser(".$row['id'].")' data-toggle='tooltip' title='Remove'><i class='far fa-trash'></i></button>
                    </td>
                </tr>
                ";
                $number++;
            }
        }
        $data .= "
            </tbody>
            </table>
            </div>
        ";
        // $data .= "</table>";
        echo $data;
    }

    // * ====================================
    // * APPROVE USERS
    // * ====================================
    if(isset($_POST['approveid'])){
        $approveid = $_POST['approveid'];
        $sql = "UPDATE `user` SET `status`='verified' WHERE `id`= '$approveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * DIS-APPROVE USERS
    // * ====================================
    if(isset($_POST['disapproveid'])){
        $disapproveid = $_POST['disapproveid'];
        $sql = "UPDATE `user` SET `status`='not verified' WHERE `id`= '$disapproveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * DELETE USERS
    // * ====================================
    if(isset($_POST['deleteid'])){
        $deleteid = $_POST['deleteid'];
        $sql = "DELETE `user`, `user_info` FROM `user`
                INNER JOIN `user_info`
                ON `user`.`email` = `user_info`.`email` AND `user`.`userType` = `user_info`.`userType`
                WHERE (`user`.`userType` <> 'admin' OR `user`.`userType` <> 'superadmin') AND `user`.`id` = '$deleteid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * READ SINGLE RECORD
    // * ====================================
    if(isset($_POST['userid'])){
        $userid = $_POST['userid'];
        $sql = "SELECT `user`.*, `user_info`.*
                FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` AND `user`.`userType` = `user_info`.`userType`
                WHERE `user`.`id` = '$userid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }