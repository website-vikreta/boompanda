<?php
include_once "./db.php";
extract($_POST);

// * ====================================
// * READ RECORDS
// * ====================================
if (!empty($_POST['readrecord']) && !empty($_POST['applicationId'])) {
    $applicationId = $_POST['applicationId'];
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
    $sql = "SELECT * FROM `applications` WHERE `taskid` = '$applicationId' AND (`status` = 'accepted' OR `status` = 'completed') ORDER BY `status` DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {

            $gigsql = "SELECT `gigLogo`, `title` FROM `tasks` WHERE `id` = '" . $row['taskid'] . "'";
            $gigres = mysqli_query($conn, $gigsql);
            $row1 = mysqli_fetch_assoc($gigres);
            $usersql = "SELECT `name` FROM `user` WHERE `email` = '" . $row['email'] . "' AND `userType` = '" . $row['userType'] . "'";
            $userres = mysqli_query($conn, $usersql);
            $row2 = mysqli_fetch_assoc($userres);
            $countsql = "SELECT `id` FROM `submissions` WHERE `email` = '" . $row['email'] . "' AND `userType` = '" . $row['userType'] . "' AND `taskid` = '" . $row['taskid'] . "' AND `status` = 'not approved' ";
            $countres = mysqli_query($conn, $countsql);
            $data .= "
                    <tr>
                        <td class='text-center'>" . $number . "</td>
                        <td class='text-center'><img src='" . substr($row1['gigLogo'], 1) . "' class='img-fluid' style='height: 20px'></td>
                        <td>" . $row1['title'] . "</td>
                        <td>" . $row2['name'] . "</td>
                        <td>" . $row['college_name'] . "</td>
                        <td>" . $row['city'] . ", " . $row['state'] . "</td>
                        <td style='font-family:var(--font-poppins);'>" . $row['UID'] . "</td>
                        <td class='text-danger font-weight-bold text-center' style='font-family:var(--font-poppins); font-size:1rem'>" . mysqli_num_rows($countres) . "</td>
                        <td class='d-flex justify-content-center p-2' style='height: 100%'>                       
                            <button class='btn solid rounded btn-primary user-" . $row['id'] . "' title='View student submissions' id='view" . $row['id'] . "' onclick='ViewUser(" . $row['id'] . ")' data-toggle='modal' data-target='#view-submissions-modal'><i class='far fa-eye'></i></button>
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
if (isset($_POST['userinfo'])) {
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
    while ($row1 = mysqli_fetch_assoc($res1)) {
        array_push($submission, $row1);
    }
    // fetching application id & boomcoind
    $sql2 = "SELECT `id`, `pending_boomcoins`, `disbursed_boomcoins` FROM `applications` WHERE  `email` = '$u_email' AND `userType` = '$u_userType' AND `taskid` = '$u_taskid'";
    $res2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($res2);

    // count of accepted submissions
    $sqlstat = "SELECT COUNT(*) AS `accepted_submissions` FROM `submissions` WHERE `email` = '$u_email' AND `userType` = '$u_userType' AND `taskid` = '$u_taskid' AND `status` = 'accepted'";
    $sqlstatres = mysqli_query($conn, $sqlstat);
    $sqlstatrow = mysqli_fetch_assoc($sqlstatres);


    $output = array();
    array_push($output, $row);
    array_push($output, $submission);
    array_push($output, $row2);
    array_push($output, count($submission));
    array_push($output, $sqlstatrow);
    array_push($output, $row2['pending_boomcoins']);
    array_push($output, $row2['disbursed_boomcoins']);
    echo json_encode($output);
}

// * ====================================
// * APPROVE SUBMISSION
// * ====================================
if (isset($_POST['approveid'])) {
    $approveid = $_POST['approveid'];
    // getting status
    $statusflag = "SELECT `flag`, `taskid`, `email`, `userType` FROM `submissions` WHERE `id` = '$approveid'";
    $r = mysqli_fetch_assoc(mysqli_query($conn, $statusflag));
    $taskid = $r['taskid'];
    $u_email = $r['email'];
    $u_userType = $r['userType'];
    // getting boomcoins
    $b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `boomcoins` FROM `tasks` WHERE `id` = '$taskid'"));
    $boomcoins = $b['boomcoins'];

    if ($r['flag'] == 0) {
        mysqli_query($conn, "UPDATE `tasks` SET `noOfApproved` = `noOfApproved` + 1 WHERE `id` = '$taskid'");
        mysqli_query($conn, "UPDATE `applications` SET `pending_boomcoins` = `pending_boomcoins` + '$boomcoins' WHERE `email` = '$u_email' AND `userType` = '$u_userType' AND `taskid` = '$taskid'");
        $sql = "UPDATE `submissions` SET `status`= 'accepted', `deleteReason` = '', `flag` = 1 WHERE `id` = '$approveid'";
    } else {
        $sql = "UPDATE `submissions` SET `status`= 'accepted', `deleteReason` = '' WHERE `id` = '$approveid'";
    }

    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}

// * ====================================
// * REJECT SUBMISSION
// * ====================================
if (isset($_POST['rejectid'])) {
    $rejectid = $_POST['rejectid'];
    $reason = $_POST['reason'];
    // getting status
    $statusflag = "SELECT `flag`, `taskid`, `email`, `userType` FROM `submissions` WHERE `id` = '$rejectid'";
    $r = mysqli_fetch_assoc(mysqli_query($conn, $statusflag));
    $taskid = $r['taskid'];
    $u_email = $r['email'];
    $u_userType = $r['userType'];
    // getting boomcoins
    $b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `boomcoins` FROM `tasks` WHERE `id` = '$taskid'"));
    $boomcoins = $b['boomcoins'];

    if ($r['flag'] == 1) {
        mysqli_query($conn, "UPDATE `tasks` SET `noOfApproved` = `noOfApproved` - 1 WHERE `id` = '$taskid'");
        mysqli_query($conn, "UPDATE `applications` SET `pending_boomcoins` = `pending_boomcoins` - '$boomcoins' WHERE `email` = '$u_email' AND `userType` = '$u_userType' AND `taskid` = '$taskid'");
        $sql = "UPDATE `submissions` SET `status`= 'rejected', `deleteReason` = '$reason', `flag` = 0 WHERE `id` = '$rejectid'";
    } else {
        $sql = "UPDATE `submissions` SET `status`= 'rejected', `deleteReason` = '$reason' WHERE `id` = '$rejectid'";
    }


    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}
