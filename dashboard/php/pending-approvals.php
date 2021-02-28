<?php
    include_once "./db.php";
    extract($_POST);

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord']) && !empty($_POST['applicationId'])){
        $applicationId = $_POST['applicationId'];
        $data = "
        <div class='table-responsive'>
            <table class='table table-sm table-striped' id='myTable' width='100%' style='font-size: 0.8rem'>
                <thead>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td style='max-width:30px'><b>Gig Image</b></td>
                    <td><b>Gig Title</b></td>
                    <td><b>Students Name</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Branch</b></td></td>
                    <td style='max-width:30px'><b>Year</b></td></td>
                    <td><b>Location</b></td></td>
                    <td><b>Status</b></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td style='max-width:30px'><b>Gig Image</b></td>
                    <td><b>Gig Title</b></td>
                    <td><b>Students Name</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Branch</b></td></td>
                    <td style='max-width:30px'><b>Year</b></td></td>
                    <td><b>Location</b></td></td>
                    <td><b>Status</b></td>
                    <td class='text-center'><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT * FROM `applications` WHERE `taskid` = '$applicationId' AND `status` <> 'deleted' ORDER BY `status` DESC";
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
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td class='text-center'><img src='".substr($row1['gigLogo'], 1)."' class='img-fluid' style='height: 20px'></td>
                        <td>".$row1['title']."</td>
                        <td>".$row2['name']."</td>
                        <td>".$row['college_name']."</td>
                        <td>".$row['course']."</td>
                        <td class='text-center'>".$row['year']."</td>
                        <td>".$row['city'].", ".$row['state']."</td>
                        <td>".$row['status']."</td>
                        <td class='d-flex justify-content-center p-2' style='height: 100%'>
                ";
                $data .= "                        
                        <button class='btn solid rounded btn-primary user-".$row['id']."' title='View student profile' id='view".$row['id']."' onclick='ViewUser(".$row['id'].")' data-toggle='modal' data-target='#view-user-modal'><i class='far fa-eye'></i></button> ";
                if($row['status'] == 'under review'){
                    $data .= "<button class='btn solid rounded btn-success user-".$row['id']."' id='approve".$row['id']."' onclick='ApproveUser(".$row['id'].")' data-toggle='tooltip' title='Allow student for submission'><i class='far fa-check'></i></button>";
                }
                $data .= "<button class='btn solid rounded btn-danger user-".$row['id']."' id='delete".$row['id']."' onclick='DeleteUser(".$row['id'].")' data-toggle='tooltip' title='Delete Application'><i class='far fa-trash'></i></button>
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
    // * APPROVE USERS
    // * ====================================
    if(isset($_POST['approveid'])){
        $approveid = $_POST['approveid'];
        $sql = "UPDATE `applications` SET `status`= 'accepted' WHERE `id` = '$approveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            $tasksql = "SELECT * FROM `tasks` WHERE `id` = (SELECT `taskid` FROM `applications` WHERE `id` = '$approveid')";
            $res = mysqli_query($conn, $tasksql);
            $r = mysqli_fetch_assoc($res);
            $emailsql = "SELECT `email` FROM `applications` WHERE `id` = '$approveid'";
            $res1 = mysqli_query($conn, $emailsql);
            $r1 = mysqli_fetch_assoc($res1);
            include_once "./actions/sendemail.php";
            $subject = "Boompanda - Update regarding your application";
            $body = "
                <h2>Hurrayyyy!</h2>
                <p>Your application for <b>".$r['title']."</b> has been accepted.</p>
                <p>(<a href = '".$r['tutorialLink']."'>Click Here</a>) to know how to perform this task.</p>
                <p>Kindly login to your boompanda portal for more information & submit the task to earn credits.</p>
            ";
            $emailsend = sendEmail($r1['email'], $subject, $body);
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
        $sql = "UPDATE `applications` SET `status`= 'deleted' WHERE `id` = '$deleteid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }


    // * ====================================
    // * READ USER INFO
    // * ====================================
    if(isset($_POST['userinfo'])){
        $userid = $_POST['userinfo'];
        $sql = "SELECT `email`, `userType` FROM `applications` WHERE `id` = '$userid'";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        $email = $row['email'];
        $userType = $row['userType'];
        $sql = "SELECT `user`.*, `user_info`.*
                FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` AND `user`.`userType` = `user_info`.`userType`
                WHERE `user`.`email` = '$email' AND `user`.`userType` = '$userType'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }