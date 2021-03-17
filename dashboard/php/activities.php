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
        $sql = "SELECT * FROM `activities` WHERE `status` = 'Active' ORDER BY `id` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <div class='card'>
                        <div class = 'head'>
                            <div class='image p-1'>
                                <img src='".substr($row['logo'], 1)."' class='img-fluid p-0' style='border-radius: 50px'>
                            </div>
                            <h3 class='gig-title'>".$row['title']."</h3>
                        </div>
                        <div class='time flex-center justify-content-between'>
                            <span class='poppins'><i class='far fa-calendar-week mr-1'></i> ".$row['startDate']."</span>
                            <span class='poppins'><i class='far fa-clock mr-1'></i> ".$row['time']."</span>
                        </div>
                        <a>".$row['category']."</a>

                        <button class='btn solid' id='view".$row['id']."' onclick='ViewActivity(".$row['id'].")' data-toggle='modal' data-target='#view-activity-modal'>Apply Now</button>
                    </div>
                ";
                    
            }
        }else{
            $data .= "<p class='text-muted text-center small p-5 w-100'>No active activities available at this moment, try again after some time.</p>";
        }
        $data .= "</div>";
        // $data .= "</table>";
        echo $data;
    }

    // * ====================================
    // * READ SINGLE RECORD
    // * ====================================
    if(isset($_POST['viewid'])){
        $viewid = $_POST['viewid'];
        $sql = "SELECT * FROM `activities` WHERE `id` = '$viewid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $sql = "SELECT `user`.`name`, `user_info`.* FROM `user_info` INNER JOIN `user` WHERE `user_info`.`email` = '$email' AND `user_info`.`userType` = '$userType' AND `user`.`email` = `user_info`.`email` AND `user`.`userType` = `user_info`.`userType`";
        $result = mysqli_query($conn, $sql);
        $r = mysqli_fetch_assoc($result);
        $row['name'] = $r['name'];
        $row['mobile'] = $r['mobile_number'];
        $row['email'] = $r['email'];
        $row['state'] = $r['state'];
        $row['city'] = $r['city'];
        $row['college_name'] = $r['college_name'];
        echo json_encode($row);
    }

    // * ====================================
    // * APPLY
    // * ====================================
    if(isset($_POST['activityId'])){
        $id = $_POST['activityId'];
        $teamSize = $_POST['teamSize'];
        $members = $_POST['members'];
        $approval = $_POST['approval']=='Yes'?'Active': 'Under Review';
        // user details
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $college = $_POST['college'];

        $response = array();
        $response['success'] = false;

        $sql = "SELECT * FROM `activity_applications` WHERE `email` = '$email' AND `mobile` = '$mobile' AND `activityid` = '$id'";
        if(mysqli_num_rows(mysqli_query($conn, $sql)) > 0){
            $response['modalErr'] = "You already applied for this activity";
        }else{
            $sql = "INSERT INTO `activity_applications`(`activityid`, `name`, `email`, `mobile`, `state`, `city`, `college`, `members`, `status`) 
                    VALUES ('$id', '$name', '$email', '$mobile', '$state','$city', '$college', '$members', '$approval')";
            $result = mysqli_query($conn, $sql);
            if($result){
                $response['success'] = true;
            }
        }

        echo json_encode($response);
    }