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
            $body = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="preconnect" href="https://fonts.gstatic.com">
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"> 
                    <style>
                        *{
                            margin: 0;
                            padding: 0;
                        }
                        .wrapper{
                            max-width: 600px;
                            margin:auto;
                            font-family: "Poppins", sans-serif;;
                            padding: 0.5rem 1rem;
                        }
                        .wrapper a{
                            text-decoration:none;
                            color: #ea1821;
                        }
                        .wrapper img{
                            margin-top: 1rem;
                        }
                        .wrapper hr{
                            border-color: #ddd;
                            margin: 1rem 0;
                        }
                        .wrapper h1{
                            font-weight: bold;
                            text-transform: capitalize;
                            color: #333;
                            margin-top: 1.5rem;
                            font-size: 1.5rem;
                        }
                        .wrapper p{
                            color: #555;
                            font-size: 0.9rem;
                            margin: 0.25rem 0;
                        }
                        .wrapper .dark{
                            background: #222;
                            padding: 2rem;
                            margin-top: 2rem;
                        }
                        .wrapper .dark p{
                            color: #ddd;
                            font-size: 0.8rem;
                            margin:  0 0;
                            text-align: center;
                        }
                    </style>
                </head>
                <body>
                    <div class="wrapper">
                        <img src="http://www.boompanda.in/assets/logo.png" class="img-fluid" alt="" style="max-width:200px">
                        <hr>
                        <h1>Hey there,</h1>
                        <p>Your application for <b>'.$r["title"].'</b> has been accepted.</p>
                        <p>(<a href = "'.$r["tutorialLink"].'">Click Here</a>) to know how to perform this task.</p>
                        <p>Kindly login to your boompanda portal for more information & submit the task to earn credits.</p>
                        <div class="dark">
                            <p>
                                Copyright © 2021 BoomPanda (Gladius Ventures LLP), All rights reserved.
                            </p>
                            <p>
                                If you don\'t recognize this mail, you can write to team@boompanda.in 
                            </p>
                        </div>
                    </div>
                </body>
                </html>
            ';
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