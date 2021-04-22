<?php

include_once "./db.php";
extract($_POST);

// getting session variables
$email = $_SESSION['email'];
$userType = $_SESSION['userType'];

// * ====================================
// * READ RECORDS
// * ====================================
if (!empty($_POST['readrecord'])) {
    $data = "<div class='flex-wrapper'>";
    // sql query with inner join
    $sql = "SELECT * FROM `tasks` WHERE `status` = 'Active' ORDER BY `id` DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data .= "
                    <div class='card'>
                        <div class='amount'><i class='far fa-life-ring'></i> " . $row['boomcoins'] . "</div>
                        <div class = 'head'>
                            <div class='image'>
                                <img src='" . substr($row['gigLogo'], 1) . "' class='img-fluid'>
                            </div>
                            <h3 class='gig-title'>" . $row['title'] . "</h3>
                        </div>
                        <div class='time flex-center justify-content-between'>
                            <span><i class='far fa-calendar-week'></i> " . $row['startDate'] . "</span>
                            <span><i class='far fa-chart-bar'></i> " . $row['complexity'] . "</span>
                        </div>
                        <a>" . $row['category'] . "</a>

                        <button class='btn solid' id='view" . $row['id'] . "' onclick='ViewTask(" . $row['id'] . ")' data-toggle='modal' data-target='#view-task-modal'>Apply Now</button>
                    </div>
                ";
        }
    } else {
        $data .= "<p class='text-muted text-center small p-5 w-100'>
                <img src = './assets/no task.png' class='img-fluid w-50'>
            </p>";
    }
    $data .= "</div>";
    // $data .= "</table>";
    echo $data;
}

// * ====================================
// * APPLIED TASKS
// * ====================================
if (!empty($_POST['readapplied'])) {
    $data = "<div class='flex-wrapper'>";
    // sql query with inner join
    $sql = "SELECT * FROM `applications` WHERE `email` = '$email' AND `userType` = '$userType' ORDER BY `id` DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row1 = mysqli_fetch_assoc($result)) {
            $sql = "SELECT * FROM `tasks` WHERE `id` = '" . $row1['taskid'] . "'";
            $res = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($res);
            if ($row['status'] != 'Paused') {
                $data .= "
                        <div class='card'>
                            <div class='amount'><i class='far fa-life-ring'></i> " . $row['boomcoins'] . "</div>
                            <div class = 'head'>
                                <div class='image'>
                                    <img src='" . substr($row['gigLogo'], 1) . "' class='img-fluid'>
                                </div>
                                <h3 class='gig-title'>" . $row['title'] . "</h3>
                            </div>
                            <div class='time flex-center justify-content-between'>
                                <span><i class='far fa-calendar-week'></i> " . $row['startDate'] . "</span>
                                <span><i class='far fa-chart-bar'></i> " . $row['complexity'] . "</span>
                            </div>
                            <a>" . $row['category'] . "</a>
                            <hr>
                            <div class='flex-center justify-content-between btn-group'>";

                if ($row1['status'] == 'accepted') {
                    $data .= "<button class='btn submit w-80' id='view" . $row['id'] . "' onclick='SubmitTask(" . $row['id'] . ")' data-toggle='modal' data-target='#submit-task-modal' title='Submit task proofs'>Submit Task</button>";
                    $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' onclick='ViewTask(" . $row['id'] . ")' data-toggle='modal' data-target='#view-task-modal' title='View gig information'><i class='fas fa-eye'></i></button>";
                } else if ($row1['status'] == 'submitted') {
                    $data .= "<span class='btn solid w-80'>" . $row1['status'] . "</span>";
                    $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' onclick='ViewTaskSubmission(" . $row['id'] . ")' data-toggle='modal' data-target='#view-submission-modal' title='View submissions'><i class='fas fa-tasks'></i></button>";
                } else {
                    $data .= "<span class='btn solid w-80'>" . $row1['status'] . "</span>";
                    $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' onclick='ViewTask(" . $row['id'] . ")' data-toggle='modal' data-target='#view-task-modal' title='View gig information'><i class='fas fa-eye'></i></button>";
                }

                $data .= "    
                            </div>
                        </div>
                    ";
            }
        }
    } else {
        $data .= "<p class='text-muted text-center small p-5 w-100'>You havn't applied to any task / gig yet. Apply to task by click Active task button on top.</p>";
    }
    $data .= "</div>";
    // $data .= "</table>";
    echo $data;
}


// * ====================================
// * COMPLETED TASKS
// * ====================================
if (!empty($_POST['readcompleted'])) {
    $data = "<div class='flex-wrapper'>";
    // sql query with inner join
    $sql = "SELECT * FROM `applications` WHERE `email` = '$email' AND `userType` = '$userType' ORDER BY `id` DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row1 = mysqli_fetch_assoc($result)) {
            $sql = "SELECT * FROM `tasks` WHERE `id` = '" . $row1['taskid'] . "' AND `status` = 'Paused'";
            $res = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($res);
            if ($row['status'] == 'Paused') {
                $data .= "
                        <div class='card'>
                            <div class='amount'><i class='far fa-life-ring'></i> " . $row['boomcoins'] . "</div>
                            <div class = 'head'>
                                <div class='image'>
                                    <img src='" . substr($row['gigLogo'], 1) . "' class='img-fluid'>
                                </div>
                                <h3 class='gig-title'>" . $row['title'] . "</h3>
                            </div>
                            <div class='time flex-center justify-content-between'>
                                <span><i class='far fa-calendar-week'></i> " . $row['startDate'] . "</span>
                                <span><i class='far fa-chart-bar'></i> " . $row['complexity'] . "</span>
                            </div>
                            <a>" . $row['category'] . "</a>
                            <hr>
                            <div class='flex-center justify-content-between btn-group'>";

                if ($row1['status'] == 'accepted') {
                    $data .= "<span class='btn solid w-80' style='cursor:pointer !important;' id='view" . $row['id'] . "' onclick='ViewTaskSubmission(" . $row['id'] . ")' data-toggle='modal' data-target='#view-submission-modal' title='View submissions'><i class='fas fa-tasks'></i> View Submissions</span>";
                    $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' onclick='ViewTask(" . $row['id'] . ")' data-toggle='modal' data-target='#view-task-modal' title='View gig information'><i class='fas fa-eye'></i></button>";
                } else if ($row1['status'] == 'submitted') {
                    $data .= "<span class='btn solid w-80'>" . $row1['status'] . "</span>";
                    $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' onclick='ViewTaskSubmission(" . $row['id'] . ")' data-toggle='modal' data-target='#view-submission-modal' title='View submissions'><i class='fas fa-tasks'></i></button>";
                } else {
                    $data .= "<span class='btn solid w-80'>" . $row1['status'] . "</span>";
                    $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' onclick='ViewTask(" . $row['id'] . ")' data-toggle='modal' data-target='#view-task-modal' title='View gig information'><i class='fas fa-eye'></i></button>";
                }

                $data .= "    
                            </div>
                        </div>
                    ";
            }
        }
    } else {
        $data .= "<p class='text-muted text-center small p-5 w-100'>You havn't applied to any task / gig yet. Apply to task by click Active task button on top.</p>";
    }
    $data .= "</div>";
    // $data .= "</table>";
    echo $data;
}

// * ====================================
// * READ SINGLE RECORD
// * ====================================
if (isset($_POST['taskid'])) {
    $taskid = $_POST['taskid'];
    $sql = "SELECT * FROM `tasks` WHERE `id` = '$taskid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    echo json_encode($row);
}
if (isset($_POST['taskid1'])) {
    $taskid = $_POST['taskid1'];
    $sql = "SELECT * FROM `tasks` WHERE `id` = '$taskid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $sql1 = "SELECT * FROM `submissions` WHERE `email` = '$email' AND `userType` = '$userType' AND `taskid` = '$taskid'";
    $res1 = mysqli_query($conn, $sql1);
    $submission = array();
    while ($row1 = mysqli_fetch_assoc($res1)) {
        array_push($submission, $row1);
    }

    $sqlstat = "SELECT COUNT(*) AS `accepted_submissions` FROM `submissions` WHERE `email` = '$email' AND `userType` = '$userType' AND `taskid` = '$taskid' AND `status` = 'accepted'";
    $sqlstatres = mysqli_query($conn, $sqlstat);
    $sqlstatrow = mysqli_fetch_assoc($sqlstatres);

    // fetching application id & boomcoind
    $sql2 = "SELECT `pending_boomcoins`, `disbursed_boomcoins` FROM `applications` WHERE  `email` = '$email' AND `userType` = '$userType' AND `taskid` = '$taskid'";
    $res2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($res2);

    $output = array();
    array_push($output, $row);
    array_push($output, $submission);
    array_push($output, count($submission));
    array_push($output, $sqlstatrow);
    array_push($output, $row2['pending_boomcoins']);
    array_push($output, $row2['disbursed_boomcoins']);
    echo json_encode($output);
}

// * ====================================
// * APPLY FOR TASK
// * ====================================
if (isset($_POST['applyid'])) {
    $applyid = $_POST['applyid'];
    $response = array();
    $response['success'] = false;
    $flag = 0;

    $checksql = "SELECT * FROM `applications` WHERE `email` = '$email' AND `userType` = '$userType' AND `taskid` = '$applyid'";
    $checkres = mysqli_query($conn, $checksql);
    if (mysqli_num_rows($checkres) > 0) {
        $response['modalErr'] = "You cannot apply again. We encounter with duplicate application. Check applied tasks or contact admins.";
        $flag = 1;
    } else {
        $usersql = "SELECT * FROM `user_info` WHERE `email` = '$email' AND `userType` = '$userType'";
        $userres = mysqli_query($conn, $usersql);
        $row = mysqli_fetch_assoc($userres);

        if ($row) {
            $college_name = $row['college_name'];
            $college_name = str_replace("'", '', $college_name);
            $course = $row['course'];
            $year = $row['year'];
            $state = $row['state'];
            $city = $row['city'];
            $uid = $row['uid'];

            if (empty($college_name) or empty($course) or empty($year) or empty($state) or empty($city) or empty($uid)) {
                $response['modalErr'] = "You cannot apply. Kindly complete your profile first.";
                $flag = 1;
            }
        } else {
            $response['modalErr'] = "Something went wrong try again later";
            $flag = 1;
        }
    }

    if ($flag == 0) {
        $sql = "INSERT INTO `applications`(`email`, `userType`, `UID`, `college_name`, `course`, `year`, `state`, `city`, `taskid`, `status`) VALUES ('$email', '$userType', '$uid', '$college_name', '$course', '$year', '$state', '$city', '$applyid', 'under review')";
        // $result = mysqli_query($conn, $sql);
        $response['modalErr'] = $applyid;
        if (mysqli_query($conn, $sql)) {
            mysqli_query($conn, "UPDATE `tasks` SET `noOfApplications` = `noOfApplications` + 1 WHERE `id` = '$applyid'");
            $response['success'] = true;
        } else {
            $response['modalErr'] = "Something went wrong. Try again later (line2)";
            // $response['modalErr'] = $college_name;
        }
    }

    echo json_encode($response);
}

// * ====================================
// * SUBMIT TASK
// * ====================================
if (isset($_POST['submit_task'])) {
    $taskid = $_POST['submit_task'];
    $proof_array = json_decode($_POST['proof_array'], true);
    // print_r($proof_array);
    $response = array();
    $response['success'] = false;
    // $response['proof_array'] = json_decode($proof_array);
    $flag = 0;
    for ($i = 0; $i < count($proof_array); $i++) {
        $today = date('Y-m-d');
        // some values
        $response[$i] = $proof_array[$i];
        $name = $proof_array[$i]['name'];
        $pemail = $proof_array[$i]['email'];
        $mobile = $proof_array[$i]['mobile'];
        $state = $proof_array[$i]['state'];
        $city = $proof_array[$i]['city'];
        // $college_name = $proof_array[$i]['college_name'];
        $college_name = str_replace("'", '', $proof_array[$i]['college_name']);
        $details = $proof_array[$i]['details'];
        $proof = $proof_array[$i]['sample_proofs'];
        $sql = "INSERT INTO `submissions` (`email`, `userType`, `taskid`, `name`, `pemail`, `dateOfSubmission`, `mobile`, `state`, `city`, `college`, `details`, `proofs`, `status`) VALUES ('$email', '$userType', '$taskid', '$name', '$pemail','$today', '$mobile', '$state', '$city', '$college_name', '$details', '$proof', 'not approved')";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            $flag = 2;
        }
    }
    $cnt = count($proof_array);
    mysqli_query($conn, "UPDATE `tasks` SET `noOfSubmissions` = `noOfSubmissions` + '$cnt' WHERE `id` = '$taskid'");
    if ($flag == 2) {
        $response['success'] = "Some entries are not uploaded. Try again later";
    } else {
        $response['success'] = true;
    }


    echo json_encode($response);
}

// * ====================================
// * READ SUBMISSIONS
// * ====================================
if (isset($_POST['submissionid'])) {
    $submissionid = $_POST['submissionid'];
    $sql = "SELECT * FROM `tasks` WHERE `id` = '$submissionid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $sql1 = "SELECT * FROM `submissions` WHERE `email` = '$email' AND `userType` = '$userType' AND `taskid` = '$submissionid'";
    $res1 = mysqli_query($conn, $sql1);
    $submission = array();
    while ($row1 = mysqli_fetch_assoc($res1)) {
        array_push($submission, $row1);
    }

    $output = array();
    array_push($output, $row);
    array_push($output, $submission);
    echo json_encode($output);
}

// * ====================================
// * DELETE SUBMISSIONS
// * ====================================
if (isset($_POST['deleteSubmissionId'])) {
    $deleteSubmissionId = $_POST['deleteSubmissionId'];
    $response = array();
    $a = "SELECT `taskid` FROM `submissions` WHERE `id` = '$deleteSubmissionId'";
    $r = mysqli_fetch_assoc(mysqli_query($conn, $a));
    $d = $r['taskid'];
    $sql = "DELETE FROM `submissions` WHERE `id` = '$deleteSubmissionId'";
    if (mysqli_query($conn, $sql)) {
        mysqli_query($conn, "UPDATE `tasks` SET `noOfSubmissions` = `noOfSubmissions` - 1 WHERE `id` = '$d'");
        $response['success'] = true;
    }

    echo json_encode($response);
}
