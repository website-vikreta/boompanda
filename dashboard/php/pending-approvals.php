<?php
    include_once "./db.php";
    extract($_POST);

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){

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
        $sql = "SELECT * FROM `applications` WHERE `status` = 'under review'";
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
                        <button class='btn solid rounded btn-primary task-".$row['id']."' title='View complete task information' id='view".$row['id']."' onclick='ViewTask(".$row['id'].")' data-toggle='modal' data-target='#view-task-modal'><i class='far fa-eye'></i></button>
                        <button class='btn solid rounded btn-success user-".$row['id']."' id='approve".$row['id']."' onclick='ApproveUser(".$row['id'].")' data-toggle='tooltip' title='Verify'><i class='far fa-check'></i></button>
                        <button class='btn solid rounded btn-danger task-".$row['id']."' id='delete".$row['id']."' onclick='DeleteAdmin(".$row['id'].")' data-toggle='tooltip' title='Remove'><i class='far fa-trash'></i></button>
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
        $sql = "UPDATE `tasks` SET `status`='Active' WHERE `id`= '$approveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }
    // * ====================================
    // * INACTIVE TASKS
    // * ====================================
    if(isset($_POST['disapproveid'])){
        $disapproveid = $_POST['disapproveid'];
        $sql = "UPDATE `tasks` SET `status`='Not Active' WHERE `id`= '$disapproveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }