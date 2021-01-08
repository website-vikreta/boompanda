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

                if(empty($college_name) OR empty($course) OR empty($year) OR empty($state) OR empty($city)){
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
                    VALUES ('$email', '$userType', '000000', '$college_name', '$course', '$year', '$state', '$city', '$applyid', 'under review')";
            $result = mysqli_query($conn, $sql);
            if($result){
                $response['success'] = true;
            }
        }

        echo json_encode($response);
    }