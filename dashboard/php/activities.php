<?php

include_once "./db.php";
require('razorpay/config.php');
require('razorpay/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

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
    $sql = "SELECT * FROM `activities` WHERE `status` = 'Active' ORDER BY `id` DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $amount = $row['type'] == 'Paid' ? "₹ " . $row['amountPaid'] : 'Free';
            $data .= "
                    <div class='card mb-4 mt-0' style='width:235px'>
                        <div class='amount'>" . $amount . "</div>   
                        <div class = 'head rect'>
                            <div class='image rect p-1'>
                                <img src='" . substr($row['thumbnail'], 1) . "' class='img-fluid p-0' style='border-radius: 50px'>
                            </div>
                            <h3 class='gig-title mx-2 mb-2'>" . $row['title'] . "</h3>
                        </div>
                        <div class='time flex-center justify-content-between'>
                            <span class='poppins'><i class='far fa-calendar-week mr-1'></i> " . date("d-m-Y", strtotime($row['startDate'])) . "</span>
                            <span class='poppins'><i class='far fa-clock mr-1'></i> " . to12hr($row['time']) . "</span>
                        </div>
                        <a>" . $row['category'] . "</a>

                        <button class='btn solid' id='view" . $row['id'] . "' onclick='ViewActivity(" . $row['id'] . ")' data-toggle='modal' data-target='#view-activity-modal'>Register Now</button>
                    </div>
                ";
        }
    } else {
        $data .= "<p class='text-muted text-center small p-5 w-100'>No active activities available at this moment, try again after some time.</p>";
    }
    $data .= "</div>";
    // $data .= "</table>";
    echo $data;
}

function to12hr($str)
{
    return date('h:i a', strtotime($str));
}
// * ====================================
// * READ SINGLE RECORD
// * ====================================
if (isset($_POST['viewid'])) {
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
// * APPLIED TASKS
// * ====================================
if (!empty($_POST['readapplied'])) {
    $data = "<div class='flex-wrapper'>";
    // sql query with inner join
    $sql = "SELECT * FROM `activity_applications` WHERE `email` = '$email' AND `userType` = '$userType'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row1 = mysqli_fetch_assoc($result)) {
            $sql = "SELECT * FROM `activities` WHERE `id` = '" . $row1['activityid'] . "'";
            $res = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($res);

            if (date('Y-m-d', strtotime($row['endDate'])) > date('Y-m-d')) {
                $data .= "
                        <div class='card mb-4 mt-0' style='width:235px'>
                            <div class = 'head rect'>
                                <div class='image rect p-1'>
                                    <img src='" . substr($row['thumbnail'], 1) . "' class='img-fluid p-0' style='border-radius: 50px'>
                                </div>
                                <h3 class='gig-title mx-2 mb-2'>" . $row['title'] . "</h3>
                            </div>
                            <div class='time flex-center justify-content-between'>
                                <span class='poppins'><i class='far fa-calendar-week mr-1'></i> " . $row['startDate'] . "</span>
                                <span class='poppins'><i class='far fa-clock mr-1'></i> " . to12hr($row['time']) . "</span>
                            </div>
                            <a>" . $row['category'] . "</a>
                            <hr>
                            <div class='flex-center justify-content-between btn-group'>";
                $data .= "<span class='btn solid w-80'>" . $row1['status'] . "</span>";
                $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' onclick='ViewActivity1(" . $row['id'] . ")' data-toggle='modal' data-target='#view-myactivity-modal'><i class='fas fa-eye'></i></button>";

                $data .= "    
                            </div>
                        </div>
                    ";
            } else {
                $data .= "
                        <div class='card mb-4 mt-0' style='opacity: 0.3'>
                            <div class = 'head'>
                                <div class='image p-1'>
                                    <img src='" . substr($row['logo'], 1) . "' class='img-fluid p-0' style='border-radius: 50px'>
                                </div>
                                <h3 class='gig-title'>" . $row['title'] . "</h3>
                            </div>
                            <div class='time flex-center justify-content-between'>
                                <span class='poppins'><i class='far fa-calendar-week mr-1'></i> " . $row['startDate'] . "</span>
                                <span class='poppins'><i class='far fa-clock mr-1'></i> " . $row['time'] . "</span>
                            </div>
                            <a>" . $row['category'] . "</a>
                            <hr>
                            <div class='flex-center justify-content-between btn-group'>";
                $data .= "<span class='btn solid w-80'>Ended</span>";
                $data .= "<button class='btn solid w-10' id='view" . $row['id'] . "' disabled><i class='fas fa-eye'></i></button>";

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
// * READ SINGLE RECORD 2
// * ====================================
if (isset($_POST['viewid2'])) {
    $viewid = $_POST['viewid2'];
    $sql = "SELECT * FROM `activities` WHERE `id` = '$viewid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $sql = "SELECT * FROM `activity_applications` WHERE `email` = '$email' AND `userType` = '$userType' AND `activityid` = '$viewid'";
    $result = mysqli_query($conn, $sql);
    $row['application'] = mysqli_fetch_assoc($result);
    echo json_encode($row);
}
