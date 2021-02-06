<?php

    include_once "./db.php";
    extract($_POST);

    // getting session variables
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){
        $data = "<div class='flex-wrapper'>";
        // sql query with inner join
        $sql = "SELECT * FROM `tasks` WHERE `status` = 'Active' ORDER BY `id` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <div class='card'>
                        <div class='amount'>₹ ".$row['boomcoins']."</div>
                        <div class = 'head'>
                            <div class='image'>
                                <img src='".substr($row['gigLogo'], 1)."' class='img-fluid'>
                            </div>
                            <h3 class='gig-title'>".$row['title']."</h3>
                        </div>
                        <div class='time flex-center justify-content-between'>
                            <span><i class='far fa-calendar-week'></i> ".$row['startDate']."</span>
                            <span><i class='far fa-chart-bar'></i> ".$row['complexity']."</span>
                        </div>
                        <a>".$row['category']."</a>

                        <button class='btn solid' id='view".$row['id']."' onclick='ViewTask(".$row['id'].")' data-toggle='modal' data-target='#view-task-modal'>Apply Now</button>
                    </div>
                ";
                    
            }
        }
        $data .= "</div>";
        // $data .= "</table>";
        echo $data;
    }

    // * ====================================
    // * APPLIED TASKS
    // * ====================================
    if(!empty($_POST['readapplied'])){
        $data = "<div class='flex-wrapper'>";
        // sql query with inner join
        $sql = "SELECT * FROM `applications` WHERE `email` = '$email' AND `userType` = '$userType' ORDER BY `id` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row1 = mysqli_fetch_assoc($result)){
                $sql = "SELECT * FROM `tasks` WHERE `id` = '".$row1['taskid']."'";
                $res = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($res);
                $data .= "
                    <div class='card'>
                        <div class='amount'>₹ ".$row['boomcoins']."</div>
                        <div class = 'head'>
                            <div class='image'>
                                <img src='".substr($row['gigLogo'], 1)."' class='img-fluid'>
                            </div>
                            <h3 class='gig-title'>".$row['title']."</h3>
                        </div>
                        <div class='time flex-center justify-content-between'>
                            <span><i class='far fa-calendar-week'></i> ".$row['startDate']."</span>
                            <span><i class='far fa-chart-bar'></i> ".$row['complexity']."</span>
                        </div>
                        <a>".$row['category']."</a>
                        <hr>
                        <div class='flex-center justify-content-between btn-group'>";

                        if($row1['status'] == 'accepted'){
                            $data .= "<button class='btn submit w-80' id='view".$row['id']."' onclick='SubmitTask(".$row['id'].")' data-toggle='modal' data-target='#submit-task-modal' title='Submit task proofs'>Submit Task</button>";
                        }else if($row1['status'] == 'submitted'){
                            $data .= "<span class='btn solid w-70'>".$row1['status']."</span>";
                            $data .= "<button class='btn solid w-10 mx-1' id='view".$row['id']."' onclick='ViewTask(".$row['id'].")' data-toggle='modal' data-target='#view-task-modal' title='View submissions'><i class='fas fa-tasks'></i></button>";
                        }else{
                            $data .= "<span class='btn solid w-80'>".$row1['status']."</span>";
                        }
                            
                        $data .="    <button class='btn solid w-10' id='view".$row['id']."' onclick='ViewTask(".$row['id'].")' data-toggle='modal' data-target='#view-task-modal' title='View gig information'><i class='fas fa-eye'></i></button>
                        </div>
                    </div>
                ";
                    
            }
        }else{
            $data .= "<p class='text-muted text-center small p-5 w-100'>You havn't applied to any task / gig yet. Apply to task by click Active task button on top.</p>";
        }
        $data .= "</div>";
        // $data .= "</table>";
        echo $data;
    }

    // * ====================================
    // * READ SINGLE RECORD
    // * ====================================
    if(isset($_POST['taskid'])){
        $taskid = $_POST['taskid'];
        $sql = "SELECT * FROM `tasks` WHERE `id` = '$taskid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }

    // * ====================================
    // * APPLY FOR TASK
    // * ====================================
    if(isset($_POST['applyid'])){
        $applyid = $_POST['applyid'];
        $response = array();
        $response['success'] = false;
        $flag = 0;

        $checksql = "SELECT * FROM `applications` WHERE `email` = '$email' AND `userType` = '$userType' AND `taskid` = '$applyid'";
        $checkres = mysqli_query($conn, $checksql);
        if(mysqli_num_rows($checkres) > 0){
            $response['modalErr'] = "You cannot apply again. We encounter with duplicate application. Check applied tasks.";
            $flag = 1;
        }else{
            $usersql = "SELECT * FROM `user_info` WHERE `email` = '$email' AND `userType` = '$userType'";
            $userres = mysqli_query($conn, $usersql);
            $row = mysqli_fetch_assoc($userres);

            if($row){
                $college_name = $row['college_name'];
                $course = $row['course'];
                $year = $row['year'];
                $state = $row['state'];
                $city = $row['city'];
                $uid = $row['uid'];

                if(empty($college_name) OR empty($course) OR empty($year) OR empty($state) OR empty($city) OR empty($uid)){
                    $response['modalErr'] = "You cannot apply. Kindly complete your profile first.";
                    $flag = 1;
                }
            }else{
                $response['modalErr'] = "Something went wrong try again later";
                $flag = 1;
            }
        }

        if($flag == 0){   
            $sql = "INSERT INTO `applications`(`email`, `userType`, `UID`, `college_name`, `course`, `year`, `state`, `city`, `taskid`, `status`) 
                    VALUES ('$email', '$userType', '$uid', '$college_name', '$course', '$year', '$state', '$city', '$applyid', 'under review')";
            $result = mysqli_query($conn, $sql);
            if($result){
                mysqli_query($conn, "UPDATE `tasks` SET `noOfApplications` = `noOfApplications` + 1 WHERE `id` = '$applyid'");
                $response['success'] = true;
            }
        }

        echo json_encode($response);
    }

    // * ====================================
    // * SUBMIT TASK
    // * ====================================
    if(isset($_POST['submit_task'])){
        $taskid = $_POST['submit_task'];
        $proof_array = json_decode($_POST['proof_array'], true);
        // print_r($proof_array);
        $response = array();
        $response['success'] = false;
        // $response['proof_array'] = json_decode($proof_array);
        $flag = 0;
        for($i=0; $i<count($proof_array); $i++){
            // some values
            $response[$i] = $proof_array[$i];
            $name = $proof_array[$i]['name'];
            $pemail = $proof_array[$i]['email'];
            $mobile = $proof_array[$i]['mobile'];
            $state = $proof_array[$i]['state'];
            $city = $proof_array[$i]['city'];
            $college_name = $proof_array[$i]['college_name'];
            $details = $proof_array[$i]['details'];
            
            $sql = "INSERT INTO `submissions` (`email`, `userType`, `taskid`, `name`, `pemail`, `mobile`, `state`, `city`, `college`, `details`, `proofs`, `status`) VALUES ('$email', '$userType', '$taskid', '$name', '$pemail', '$mobile', '$state', '$city', '$college_name', '$details', '', 'not approved')";
            $result = mysqli_query($conn, $sql);

            if(!$result){
                $flag = 2;
            }
        }
        if($flag == 2){
            $response['success'] = "Some entries are not uploaded. Try again later";
        }else{
            $sql1 = "UPDATE `applications` SET `status`= 'submitted' WHERE `email` = '$email' AND `userType` = '$userType' AND `taskid` = '$taskid' ";
            $result1 = mysqli_query($conn, $sql1);
            if($result1)
                $response['success'] = true;
        }
        

        echo json_encode($response);
    }