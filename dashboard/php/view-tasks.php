<?php
    include_once "./db.php";
    extract($_POST);

    $superadmin = $_SESSION['userType'];

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){

        $data = "
        <div class='table-responsive'>
            <table class='table table-sm table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td style='width:10px'><b>Gig Image</b></td>
                    <td><b>About Gig</b></td>
                    <td style='width:10px'><b>Applications</b></td></td>
                    <td style='width:10px'><b>Submissions</b></td></td>
                    <td style='width:10px'><b>Approved Submissions</b></td></td>
                    <td style='width:10px'><b>Amount Disbursed</b></td>
                    <td><b>Status</b></td>
                    <td class='text-center' style='width:220px'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td style='max-width:30px'><b>Gig Image</b></td>
                    <td><b>About Gig</b></td>
                    <td><b>Applications</b></td></td>
                    <td><b>Submissions</b></td></td>
                    <td><b>Approved Submissions</b></td></td>
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
                $task_id = $row['id'];
                $amt_d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(`disbursed_boomcoins`) AS `disbursed_boomcoins` FROM `applications` WHERE `taskid` = '$task_id'"));
                if($amt_d['disbursed_boomcoins'] == ""){$amt_d['disbursed_boomcoins'] = 0;}
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td class='text-center'><img src='".substr($row['gigLogo'], 1)."' class='img-fluid' style='height: 20px'></td>
                        <td><b>".$row['title']."</b><br><i>".$row['companyName']."</i><p class='m-0'>End Date - <span style='font-family: poppins'>".$row['endDate']."</span></td>
                        <td class='text-center poppins'>".$row['noOfApplications']."</td>
                        <td class='text-center poppins'>".$row['noOfSubmissions']."</td>
                        <td class='text-center poppins font-weight-bold'>".$row['noOfApproved']."</td>
                        <td class='text-center poppins'>".$amt_d['disbursed_boomcoins']."</td>
                        <td class='text-center'>".$row['status']."</td>";
                if($superadmin == 'superadmin'){
                    $data .= "<td class='flex-center'><button class='btn btn-solid btn-danger text-white w-100 h-auto' onclick='DisburseFunds(".$row['id'].")' title='View all pending amounts & pay' data-toggle='modal' data-target='#payment-modal'>Disburse Amount</button></td>";
                }
                        
                $data .="<td class='flex-between' style='height: 100%; display: flex;'>
                ";
                if($row['status'] == "Not Active" || $row['status'] == "Paused"){
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
                        <button class='btn solid rounded btn-info task-".$row['id']."' title='View applications' id='application".$row['id']."' onclick='window.location.href= `./pending-approvals.html?id=".$row['id']."`'><i class='far fa-tasks'></i></button>
                        <button class='btn solid rounded btn-warning task-".$row['id']."' title='View submissions' id='submission".$row['id']."' onclick='window.location.href= `./submissions.html?id=".$row['id']."`'><i class='far fa-share'></i></button>
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
        $sql = "UPDATE `tasks` SET `status`='Paused' WHERE `id`= '$disapproveid'";
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

    
    // * ====================================
    // * DISBURSD FUNDS
    // * ====================================
    if(isset($_POST['disbursedid'])){
        $disbursedid = $_POST['disbursedid'];
        $total_amt = 0;
        $data = "
        <div class='flex-center m-0' style='width:100% !important; max-height:60vh; overflow-y:scroll;'>
            <table class='table m-0 table-sm table-striped w-100' id='myTable' width='100%'>
                <thead>
                    <td><b>Sr No</b></td>
                    <td><b>Email</b></td>
                    <td><b>Pending Amount</b></td>
                </thead>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT * FROM `applications` WHERE `taskid` = '$disbursedid' ORDER BY `pending_boomcoins` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                if($row['pending_boomcoins'] > 0){
                    $total_amt = $total_amt + $row['pending_boomcoins'];
                    $data .= "
                        <tr>
                            <td class='text-center'>".$number."</td>
                            <td class=''>".$row['email']."</td>
                            <td class='text-center'>".$row['pending_boomcoins']."</td>
                        </tr>
                    ";
                    $number++;
                }
            }
        }
        $data .= "
            </tbody>
            </table>
            </div>

            <div class='content mt-3'>
                <b class='small poppins'>Total Amount to be paid: <span class='text-danger font-weight-bold'> ".$total_amt."</span></b>
            </div>
            <center class='my-3'>
                <button class='btn btn-solid' onclick='PayFunds(".$disbursedid.")'>Pay Now</button>
            </center>
        ";
        // $data .= "</table>";
        echo $data;
    }

    // * ====================================
    // * PAY NOW
    // * ====================================
    if(isset($_POST['payid'])){
        $total_pay = 0;
        $taskid = $_POST['payid'];
        $response = array();
        $response['success'] = false;
        $response['id'] = $taskid;

        $sql = "SELECT * FROM `applications` WHERE `pending_boomcoins` > 0 AND `taskid` = '$taskid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $u_email = $row['email'];
                $u_userType = $row['userType'];
                $coins = $row['pending_boomcoins'];
                mysqli_query($conn, "UPDATE `applications` SET`disbursed_boomcoins`= `disbursed_boomcoins` + `pending_boomcoins`, `pending_boomcoins`= 0 WHERE `email` = '$u_email' AND `userType` = '$u_userType' AND `taskid` = '$taskid'");
                mysqli_query($conn, "UPDATE `wallet` SET `balance`=`balance` + '$coins',`total_earning`= `total_earning` + '$coins' WHERE `email` = '$u_email' AND `userType` = '$u_userType'");
            }
            $response['success'] = true;
        }
        echo json_encode($response);
    }