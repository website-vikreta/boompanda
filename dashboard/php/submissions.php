<?php
    include_once "./db.php";
    extract($_POST);

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){

        $data = "
        <div class='table-responsive-xl'>
            <table class='table table-sm table-striped' id='myTable' width='100%' style='font-size: 0.8rem'>
                <thead>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td style='max-width:30px'><b>Gig Image</b></td>
                    <td><b>Gig Title</b></td>
                    <td><b>Students Name</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Location</b></td></td>
                    <td><b>UID</b></td></td>
                    <td><b>Pending</b></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td style='max-width:30px'><b>Gig Image</b></td>
                    <td><b>Gig Title</b></td>
                    <td><b>Students Name</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Location</b></td></td>
                    <td><b>UID</b></td></td>
                    <td><b>Pending</b></td>
                    <td class='text-center'><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT * FROM `applications` WHERE `status` = 'accepted' OR `status` = 'completed' ORDER BY `status` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){

                $gigsql = "SELECT `gigLogo`, `title` FROM `tasks` WHERE `id` = '".$row['taskid']."'";
                $gigres = mysqli_query($conn, $gigsql);
                $row1 = mysqli_fetch_assoc($gigres);
                $usersql = "SELECT `name` FROM `user` WHERE `email` = '".$row['email']."' AND `userType` = '".$row['userType']."'";
                $userres = mysqli_query($conn, $usersql);
                $row2 = mysqli_fetch_assoc($userres);
                $countsql = "SELECT `id` FROM `submissions` WHERE `email` = '".$row['email']."' AND `userType` = '".$row['userType']."' AND `taskid` = '".$row['taskid']."' AND `status` = 'not approved' ";
                $countres = mysqli_query($conn, $countsql);
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td class='text-center'><img src='".substr($row1['gigLogo'], 1)."' class='img-fluid' style='height: 20px'></td>
                        <td>".$row1['title']."</td>
                        <td>".$row2['name']."</td>
                        <td>".$row['college_name']."</td>
                        <td>".$row['city'].", ".$row['state']."</td>
                        <td style='font-family:var(--font-poppins);'>".$row['UID']."</td>
                        <td class='text-danger font-weight-bold text-center' style='font-family:var(--font-poppins); font-size:1rem'>".mysqli_num_rows($countres)."</td>
                        <td class='d-flex justify-content-center p-2' style='height: 100%'>                       
                            <button class='btn solid rounded btn-primary user-".$row['id']."' title='View student submissions' id='view".$row['id']."' onclick='ViewUser(".$row['id'].")' data-toggle='modal' data-target='#view-submissions-modal'><i class='far fa-eye'></i></button>
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
    // * READ USER INFO
    // * ====================================
    if(isset($_POST['userinfo'])){
        $userid = $_POST['userinfo'];
        $sql = "SELECT `email`, `userType`, `taskid` FROM `applications` WHERE `id` = '$userid'";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        $u_email = $row['email'];
        $u_userType = $row['userType'];
        $u_taskid = $row['taskid'];
        // fetching user details
        $sql = "SELECT `user`.*, `user_info`.*
                FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` AND `user`.`userType` = `user_info`.`userType`
                WHERE `user`.`email` = '$u_email' AND `user`.`userType` = '$u_userType'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        // fetching submissions
        $sql1 = "SELECT * FROM `submissions` WHERE `email` = '$u_email' AND `userType` = '$u_userType' AND `taskid` = '$u_taskid'";
        $res1 = mysqli_query($conn, $sql1);
        $submission = array();
        while($row1 = mysqli_fetch_assoc($res1)){
            array_push($submission, $row1);
        }
        // fetching application id
        $sql2 = "SELECT `id` FROM `applications` WHERE  `email` = '$u_email' AND `userType` = '$u_userType' AND `taskid` = '$u_taskid'";
        $res2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($res2);

        $output = array();
        array_push($output, $row);
        array_push($output, $submission);
        array_push($output, $row2);
        echo json_encode($output);
    }

    // * ====================================
    // * APPROVE SUBMISSION
    // * ====================================
    if(isset($_POST['approveid'])){
        $approveid = $_POST['approveid'];
        $sql = "UPDATE `submissions` SET `status`= 'accepted' WHERE `id` = '$approveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";          
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * REJECT SUBMISSION
    // * ====================================
    if(isset($_POST['rejectid'])){
        $rejectid = $_POST['rejectid'];
        $sql = "UPDATE `submissions` SET `status`= 'rejected' WHERE `id` = '$rejectid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";          
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * COMPLETE TASK
    // * ====================================
    if(isset($_POST['complete_task'])){
        $complete_task = $_POST['complete_task'];
        $sql = "UPDATE `applications` SET `status`= 'completed' WHERE `id` = '$complete_task'";
        $result = mysqli_query($conn, $sql);
        $sql1 = "SELECT `email` FROM `applications` WHERE `id` = '$complete_task'";
        $res1 = mysqli_query($conn, $sql1);
        $r1 = mysqli_fetch_assoc($res1);

        $response = array();
        $response['success'] = false;

        // sending email
        include_once "./actions/sendemail.php";
        $subject = "Boompanda - Acceptance of submissions.";
        $body = "
            <h2>Hurrayyyy!</h2>
            <p>We have successfully gone through you submissions. Kindly login to dashboard to see how many of them acepted & rejected. Your task is completed.</p>
            <br>
            <br>
            <p>Regards,</p>
            <p>Team Boompanda</p>
        ";
        $emailsend = sendEmail($r1['email'], $subject, $body);
        
        if($result and $emailsend){
            $response['success'] = true;
        }
        echo json_encode($response);
    }