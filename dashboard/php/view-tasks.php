<?php
    include_once "./db.php";
    extract($_POST);

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){

        $data = "
        <div class='table-responsive-xl'>
            <table class='table table-sm table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td style='max-width:30px'><b>Gig Image</b></td>
                    <td><b>Gig Title</b></td>
                    <td><b>Company Name</b></td></td>
                    <td style='max-width:30px'><b>Number of Applications</b></td></td>
                    <td style='max-width:30px'><b>Number of Submissions</b></td></td>
                    <td><b>Amount Disbursed</b></td>
                    <td><b>Status</b></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td><b>Sr No</b></td>
                    <td><b>Gig Image</b></td>
                    <td><b>Gig Title</b></td>
                    <td><b>Company Name</b></td></td>
                    <td><b>Number of Applications</b></td></td>
                    <td><b>Number of Submissions</b></td></td>
                    <td><b>Amount Disbursed</b></td>
                    <td><b>Status</b></td>
                    <td class='text-center'><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT * FROM `tasks` WHERE `status` != 'Deleted' ORDER BY `id` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td class='text-center'><img src='".substr($row['gigLogo'], 1)."' class='img-fluid' style='height: 20px'></td>
                        <td>".$row['title']."</td>
                        <td>".$row['companyName']."</td>
                        <td class='text-center'>".$row['noOfApplications']."</td>
                        <td class='text-center'>".$row['noOfSubmissions']."</td>
                        <td></td>
                        <td>".$row['status']."</td>
                        <td class='d-flex justify-content-center p-2' style='height: 100%'>
                ";
                if($row['status'] == "Not Active"){
                    $data .= "
                    <button class='btn solid rounded btn-success task-".$row['id']."' id='approve".$row['id']."' onclick='ActiveTask(".$row['id'].")' data-toggle='tooltip' title='Run this gig'><i class='far fa-play'></i></button>
                    ";
                }else if($row['status'] == "Active"){
                    $data .= "
                    <button class='btn solid rounded btn-warning task-".$row['id']."' id='disapprove".$row['id']."' onclick='InactiveTask(".$row['id'].")' data-toggle='tooltip' title='Stop running this gig'><i class='far fa-stop'></i></button>
                    ";
                }
                $data .= "                        
                        <button class='btn solid rounded btn-primary task-".$row['id']."' title='View complete task information' id='view".$row['id']."' onclick='ViewTask(".$row['id'].")' data-toggle='modal' data-target='#view-task-modal'><i class='far fa-eye'></i></button>
                        <button class='btn solid rounded btn-secondary task-".$row['id']."'><i class='far fa-edit'></i></button>
                        <button class='btn solid rounded btn-danger task-".$row['id']."' id='delete".$row['id']."' onclick='DeleteTask(".$row['id'].")' data-toggle='tooltip' title='Remove'><i class='far fa-trash'></i></button>
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

    // * ====================================
    // * DELETE TASKS
    // * ====================================
    if(isset($_POST['deleteid'])){
        $deleteid = $_POST['deleteid'];
        $sql = "UPDATE `tasks` SET `status`='Deleted' WHERE `id`= '$deleteid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }