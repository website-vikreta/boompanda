<?php

include_once "./db.php";

// getting session variables
$email = $_SESSION['email'];
$userType = $_SESSION['userType'];

extract($_POST);

// * ====================================
// * ADD ACTIVITY
// * ====================================
if (isset($_POST['addActivity']) and $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array();
    $response['success'] = false;
    $flag = 0;
    $title = $category = $organizer = $about = $startDate = $endDate = "";
    $time = $participate = $rewards = $type = $paidAmount = $team = $teamSize = "";
    $platform = $location = $approval = "";
    $organizername1 = $organizerno1 = $organizername2 = $organizerno2 = $wheretoperform = $externalLink = "";

    // validating activity logo
    $imageFileType = $filename = "";
    if (!isset($_FILES['activityLogo']['name'])) {
        $response['activityLogoErr'] = "Upload company's logo";
        $flag = 1;
    } else {
        /* Getting file name */
        $filename = $_FILES['activityLogo']['name'];
        $logoLocation = "../media/activities/" . $filename;
        $imageFileType = pathinfo($logoLocation, PATHINFO_EXTENSION);
        /* Valid Extensions */
        $valid_extensions = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        /* Check file extension */
        if (!in_array(strtolower($imageFileType), $valid_extensions)) {
            $uploadOk = 0;
            $response['activityLogoErr'] = "Unable to upload file. Invalid image format";
            $flag = 1;
        }
    }

    // validating activity title
    if (empty($_POST['title'])) {
        $response['titleErr'] = "Required!";
        $flag = 1;
    } else {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
    }

    // validating activity category
    if ($_POST['category'] == "-1") {
        $response['categoryErr'] = "Select one option";
        $flag = 1;
    } else {
        $category = mysqli_real_escape_string($conn, $_POST['category']);
    }

    // validating organizer's name
    if (empty($_POST['organizationName'])) {
        $response['organizationNameErr'] = 'Required!';
        $flag = 1;
    } else {
        $organizer = mysqli_real_escape_string($conn, $_POST['organizationName']);
        if (preg_match("/[^A-Za-z0-9 '-]/", $organizer)) {
            $response['organizationNameErr'] = "Must be alphanumeric";
            $flag = 1;
        }
    }

    // validating about activity
    if (empty($_POST['aboutActivity'])) {
        $response['aboutActivityErr'] = "Required!";
        $flag = 1;
    } else {
        $about = mysqli_real_escape_string($conn, $_POST['aboutActivity']);
    }

    // validating start date
    if (empty($_POST['startDate'])) {
        $response['startDateErr'] = 'Required!';
        $flag = 1;
    } else {
        $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    }

    // validating end date
    if (empty($_POST['endDate'])) {
        $response['endDateErr'] = 'Required!';
        $flag = 1;
    } else {
        $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
    }

    // validating time
    if (empty($_POST['time'])) {
        $response['timeErr'] = 'Required!';
        $flag = 1;
    } else {
        $time = mysqli_real_escape_string($conn, $_POST['time']);
    }

    // getting organizer contact info
    $organizername1 = mysqli_real_escape_string($conn, $_POST['organizername1']);
    $organizerno1 = mysqli_real_escape_string($conn, $_POST['organizerno1']);
    $organizername2 = mysqli_real_escape_string($conn, $_POST['organizername2']);
    $organizerno2 = mysqli_real_escape_string($conn, $_POST['organizerno2']);

    if (empty($_POST['participate'])) {
        $response['participateErr'] = "Required!";
        $flag = 1;
    } else {
        $participate = mysqli_real_escape_string($conn, $_POST['participate']);
    }

    // validating banner logo
    $imageFileType1 = $filename1 = "";
    if (!isset($_FILES['bannerImage']['name'])) {
        $response['bannerImageErr'] = "Upload banner image(1400px * 300px)";
        $flag = 1;
    } else {
        /* Getting file name */
        $filename1 = $_FILES['bannerImage']['name'];
        $logoLocation1 = "../media/activities/" . $filename1;
        $imageFileType1 = pathinfo($logoLocation1, PATHINFO_EXTENSION);
        /* Valid Extensions */
        $valid_extensions = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        /* Check file extension */
        if (!in_array(strtolower($imageFileType1), $valid_extensions)) {
            $uploadOk = 0;
            $response['bannerImageErr'] = "Unable to upload file. Invalid image format";
            $flag = 1;
        }
    }

    // validating thumbnail logo
    $imageFileType2 = $filename2 = "";
    if (!isset($_FILES['thumbnailImage']['name'])) {
        $response['thumbnailImageErr'] = "Upload thumbnail image(500px * 300px)";
        $flag = 1;
    } else {
        /* Getting file name */
        $filename2 = $_FILES['thumbnailImage']['name'];
        $logoLocation2 = "../media/activities/" . $filename2;
        $imageFileType2 = pathinfo($logoLocation2, PATHINFO_EXTENSION);
        /* Valid Extensions */
        $valid_extensions = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        /* Check file extension */
        if (!in_array(strtolower($imageFileType2), $valid_extensions)) {
            $uploadOk = 0;
            $response['thumbnailImageErr'] = "Unable to upload file. Invalid image format";
            $flag = 1;
        }
    }

    if (empty($_POST['rewards'])) {
        $response['rewardsErr'] = "Required!";
        $flag = 1;
    } else {
        $rewards = mysqli_real_escape_string($conn, $_POST['rewards']);
    }

    // validating type
    if (empty($_POST['type']) || $_POST['type'] == 'undefined') {
        $response['typeErr'] = 'Required!';
        $flag = 1;
    } else {
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        if ($type == "Paid") {
            if (empty($_POST['paidAmount'])) {
                $response['paidAmountErr'] = 'Required!';
                $flag = 1;
            } else {
                $paidAmount = mysqli_real_escape_string($conn, $_POST['paidAmount']);
            }
        }
    }

    // validating participation
    if (empty($_POST['team']) || $_POST['team'] == 'undefined') {
        $response['teamErr'] = 'Required!';
        $flag = 1;
    } else {
        $team = mysqli_real_escape_string($conn, $_POST['team']);
        if ($team == "Team") {
            if (empty($_POST['teamSize'])) {
                $response['teamSizeErr'] = 'Required!';
                $flag = 1;
            } else {
                $teamSize = mysqli_real_escape_string($conn, $_POST['teamSize']);
            }
        }
    }

    // validating platform
    if (empty($_POST['platform']) || $_POST['platform'] == 'undefined') {
        $response['platformErr'] = 'Required!';
        $flag = 1;
    } else {
        $platform = mysqli_real_escape_string($conn, $_POST['platform']);
    }

    if (empty($_POST['location'])) {
        $response['locationErr'] = "Required!";
        $flag = 1;
    } else {
        $location = mysqli_real_escape_string($conn, $_POST['location']);
    }

    // validating approval
    if (empty($_POST['approval']) || $_POST['approval'] == 'undefined') {
        $response['approvalErr'] = 'Required!';
        $flag = 1;
    } else {
        $approval = mysqli_real_escape_string($conn, $_POST['approval']);
    }

    if (empty($_POST['wheretoperform']) || $_POST['wheretoperform'] == 'undefined') {
        $response['wheretoperformErr'] = 'Required!';
        $flag = 1;
    } else {
        $wheretoperform = mysqli_real_escape_string($conn, $_POST['wheretoperform']);
        if ($wheretoperform == "Outside") {
            if (empty($_POST['external_link'])) {
                $response['external_linkErr'] = 'Required!';
                $flag = 1;
            } else {
                $externalLink = mysqli_real_escape_string($conn, $_POST['external_link']);
            }
        }
    }

    // if it pass every validation then push into db & store files
    if ($flag == 0) {

        // create directory
        $foldertimestamp = round(microtime(true));
        if (!file_exists("../media/activities/" . $foldertimestamp)) {
            mkdir("../media/activities/" . $foldertimestamp, 0777);
        }
        // copying gig logo
        $logoLocation = '../media/activities/' . $foldertimestamp . '/' . $filename;
        compress_image($_FILES['activityLogo']['tmp_name'], $logoLocation, 50);
        $bannerLocation = '../media/activities/' . $foldertimestamp . '/' . $filename1;
        compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 50);
        $thumbnailLocation = '../media/activities/' . $foldertimestamp . '/' . $filename2;
        compress_image($_FILES['thumbnailImage']['tmp_name'], $thumbnailLocation, 50);

        $token = sha1(time());
        $sql = "INSERT INTO `activities`(`title`, `logo`, `category`, `organizer`, `about_activity`, `startDate`, `endDate`, `time`, `organizername1`, `organizerno1`, `organizername2`, `organizerno2`, `participation`, `banner`, `thumbnail`, `rewards`, `type`, `amountPaid`, `team`, `teamSize`, `platform`, `location`, `external_link`, `token`, `approval`, `status`) 
                    VALUES ('$title', '$logoLocation', '$category', '$organizer', '$about', '$startDate', '$endDate', '$time', '$organizername1', '$organizerno1', '$organizername2', '$organizerno2' ,'$participate', '$bannerLocation', '$thumbnailLocation', '$rewards', '$type', '$paidAmount', '$team', '$teamSize', '$platform', '$location','$externalLink','$token', '$approval', 'Not Active')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $response['success'] = true;
        }
    }


    echo json_encode($response);
}


// image compression function
function compress_image($source_url, $destination_url, $quality)
{
    $info = getimagesize($source_url);
    if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
    elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
    elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
    imagejpeg($image, $destination_url, $quality);
    return true;
}

// * ====================================
// * READ RECORDS
// * ====================================
if (!empty($_POST['readrecord'])) {

    $data = "
            <div class='table-responsive'>
            <table class='table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td style='width:10px'><b>Activity Logo</b></td>
                    <td><b>Title</b></td></td>
                    <td><b>Organizer</b></td></td>
                    <td><b>Start Date</b></td></td>
                    <td><b>End Date</b></td></td>
                    <td><b>Number of Applications</b></td></td>
                    <td><b>Status</b></td></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td><b>Sr No</b></td>
                    <td><b>Activity Logo</b></td>
                    <td><b>Title</b></td></td>
                    <td><b>Organizer</b></td></td>
                    <td><b>Start Date</b></td></td>
                    <td><b>End Date</b></td></td>
                    <td><b>Number of Applications</b></td></td>
                    <td><b>Status</b></td></td>
                    <td><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
    // sql query with inner join
    $sql = "SELECT * FROM `activities`";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data .= "
                    <tr>
                        <td class='text-center'>" . $number . "</td>
                        <td class='text-center'><img src='" . substr($row['logo'], 1) . "' class='img-fluid' style='height: 40px'></td>
                        <td>" . $row['title'] . "</td>
                        <td>" . $row['organizer'] . "</td>
                        <td class='poppins'>" . date("d-m-Y", strtotime($row['startDate'])) . "</td>
                        <td class='poppins'>" . date("d-m-Y", strtotime($row['endDate'])) . "</td>
                        <td class='text-center'>" . $row['noOfApplication'] . "</td>
                        <td class='text-center'>" . $row['status'] . "</td>
                        <td class='d-flex justify-content-end pb-0'>
                            <a class='btn btn-solid btn-primary text-white w-100 h-auto mb-1' href='../live/activity.php?accesskey=" . $row['token'] . "' target='_BLANK' onclick='LiveTrack(" . $row['id'] . ")' title='View Live Tracking'>Live Tracking</a>     
                        </td>
                        <td class='d-flex justify-content-end'>
                            <button class='btn solid rounded btn-info activity-" . $row['id'] . "' title='View complete info' id='activity" . $row['id'] . "' onclick='viewActivity(" . $row['id'] . ")' data-toggle='modal' data-target='#view-activity-modal'><i class='far fa-eye'></i></button>
                ";
            if ($row['status'] == "Not Active") {
                $data .= "
                    <button class='btn solid rounded btn-success activity-" . $row['id'] . "' id='approve" . $row['id'] . "' onclick='activeActivity(" . $row['id'] . ")' data-toggle='tooltip' title='Active'><i class='far fa-check'></i></button>
                    ";
            } else if ($row['status'] == "Active") {
                $data .= "
                    <button class='btn solid rounded btn-warning activity-" . $row['id'] . "' id='disapprove" . $row['id'] . "' onclick='deactiveActivity(" . $row['id'] . ")' data-toggle='tooltip' title='Not Active'><i class='far fa-times'></i></button>
                    ";
            }
            $data .= "                        
                        <button class='btn solid rounded btn-primary activity-" . $row['id'] . "' title='View applications' id='application" . $row['id'] . "' onclick='window.location.href= `./pending-activities-application.html?id=" . $row['id'] . "&token=" . sha1(time()) . "&flag=" . $row['approval'] . "`'><i class='far fa-tasks'></i></button>
                        <button class='btn solid rounded btn-secondary activity-" . $row['id'] . "' id='edit" . $row['id'] . "' onclick='EditActivity(" . $row['id'] . ")' title='Edit' data-toggle='modal' data-target='#edit-activity-modal'><i class='far fa-edit'></i></button>
                        <button class='btn solid rounded btn-danger activity-" . $row['id'] . "' id='delete" . $row['id'] . "' onclick='DeleteActivity(" . $row['id'] . ")' data-toggle='tooltip' title='Delete Activity'><i class='far fa-trash'></i></button>
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
// * ACTIVE TASK
// * ====================================
if (isset($_POST['approveid'])) {
    $approveid = $_POST['approveid'];
    $sql = "UPDATE `activities` SET `status`='Active' WHERE `id`= '$approveid'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}
// * ====================================
// * INACTIVE TASKS
// * ====================================
if (isset($_POST['disapproveid'])) {
    $disapproveid = $_POST['disapproveid'];
    $sql = "UPDATE `activities` SET `status`='Not Active' WHERE `id`= '$disapproveid'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}

// * ====================================
// * DELETE TASKS
// * ====================================
if (isset($_POST['deleteid'])) {
    $deleteid = $_POST['deleteid'];
    $sql = "DELETE FROM `activities` WHERE `id` = '$deleteid'";
    $result = mysqli_query($conn, $sql);
    // delete application remaining
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}

// * ====================================
// * READ SINGLE RECORD
// * ====================================
if (isset($_POST['activityid'])) {
    $activityid = $_POST['activityid'];
    $sql = "SELECT * FROM `activities` WHERE `id` = '$activityid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
}
if (isset($_POST['editid'])) {
    $editid = $_POST['editid'];
    $sql = "SELECT * FROM `activities` WHERE `id` = '$editid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
}

// * ====================================
// * EDIT ACTIVITY
// * ====================================
if (isset($_POST['editActivity']) and $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array();
    $response['success'] = false;
    $flag = 0;
    $title = $category = $organizer = $about = $startDate = $endDate = "";
    $time = $participate = $rewards = $type = $paidAmount = $team = $teamSize = "";
    $platform = $location = $approval = "";
    $organizername1 = $organizerno1 = $organizername2 = $organizerno2 = $wheretoperform = $externalLink = "";
    $taskid = $_POST['taskid'];

    // validating activity logo
    $imageFileType = $filename = "";
    if (isset($_FILES['activityLogo']['name'])) {
        /* Getting file name */
        $filename = $_FILES['activityLogo']['name'];
        $logoLocation = "../media/activities/" . $filename;
        $imageFileType = pathinfo($logoLocation, PATHINFO_EXTENSION);
        /* Valid Extensions */
        $valid_extensions = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        /* Check file extension */
        if (!in_array(strtolower($imageFileType), $valid_extensions)) {
            $uploadOk = 0;
            $response['gigLogoErr'] = "Unable to upload file. Invalid image format";
            $flag = 1;
        }
    }

    // validating activity title
    if (empty($_POST['title'])) {
        $response['titleErr'] = "Required!";
        $flag = 1;
    } else {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
    }

    // validating activity category
    if ($_POST['category'] == "-1") {
        $response['categoryErr'] = "Select one option";
        $flag = 1;
    } else {
        $category = mysqli_real_escape_string($conn, $_POST['category']);
    }

    // validating organizer's name
    if (empty($_POST['organizationName'])) {
        $response['organizationNameErr'] = 'Required!';
        $flag = 1;
    } else {
        $organizer = mysqli_real_escape_string($conn, $_POST['organizationName']);
        if (preg_match("/[^A-Za-z0-9 '-]/", $organizer)) {
            $response['organizationNameErr'] = "Must be alphanumeric";
            $flag = 1;
        }
    }

    // validating about activity
    if (empty($_POST['aboutActivity'])) {
        $response['aboutActivityErr'] = "Required!";
        $flag = 1;
    } else {
        $about = mysqli_real_escape_string($conn, $_POST['aboutActivity']);
    }

    // validating start date
    if (empty($_POST['startDate'])) {
        $response['startDateErr'] = 'Required!';
        $flag = 1;
    } else {
        $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    }

    // validating end date
    if (empty($_POST['endDate'])) {
        $response['endDateErr'] = 'Required!';
        $flag = 1;
    } else {
        $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
    }

    // validating time
    if (empty($_POST['time'])) {
        $response['timeErr'] = 'Required!';
        $flag = 1;
    } else {
        $time = mysqli_real_escape_string($conn, $_POST['time']);
    }

    // getting organizer contact info
    $organizername1 = mysqli_real_escape_string($conn, $_POST['organizername1']);
    $organizerno1 = mysqli_real_escape_string($conn, $_POST['organizerno1']);
    $organizername2 = mysqli_real_escape_string($conn, $_POST['organizername2']);
    $organizerno2 = mysqli_real_escape_string($conn, $_POST['organizerno2']);

    if (empty($_POST['participate'])) {
        $response['participateErr'] = "Required!";
        $flag = 1;
    } else {
        $participate = mysqli_real_escape_string($conn, $_POST['participate']);
    }

    // validating banner logo
    $imageFileType1 = $filename1 = "";
    if (isset($_FILES['bannerImage']['name'])) {
        /* Getting file name */
        $filename1 = $_FILES['bannerImage']['name'];
        $logoLocation1 = "../media/activities/" . $filename1;
        $imageFileType1 = pathinfo($logoLocation1, PATHINFO_EXTENSION);
        /* Valid Extensions */
        $valid_extensions = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        /* Check file extension */
        if (!in_array(strtolower($imageFileType1), $valid_extensions)) {
            $uploadOk = 0;
            $response['bannerImageErr'] = "Unable to upload file. Invalid image format";
            $flag = 1;
        }
    }

    // validating thumbnail
    $imageFileType2 = $filename2 = "";
    if (isset($_FILES['thumbnailImage']['name'])) {
        /* Getting file name */
        $filename2 = $_FILES['thumbnailImage']['name'];
        $logoLocation2 = "../media/activities/" . $filename2;
        $imageFileType2 = pathinfo($logoLocation2, PATHINFO_EXTENSION);
        /* Valid Extensions */
        $valid_extensions = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        /* Check file extension */
        if (!in_array(strtolower($imageFileType2), $valid_extensions)) {
            $uploadOk = 0;
            $response['thumbnailImageErr'] = "Unable to upload file. Invalid image format";
            $flag = 1;
        }
    }

    if (empty($_POST['rewards'])) {
        $response['rewardsErr'] = "Required!";
        $flag = 1;
    } else {
        $rewards = mysqli_real_escape_string($conn, $_POST['rewards']);
    }

    // validating type
    if (empty($_POST['type']) || $_POST['type'] == 'undefined') {
        $response['typeErr'] = 'Required!';
        $flag = 1;
    } else {
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        if ($type == "Paid") {
            if (empty($_POST['paidAmount'])) {
                $response['paidAmountErr'] = 'Required!';
                $flag = 1;
            } else {
                $paidAmount = mysqli_real_escape_string($conn, $_POST['paidAmount']);
            }
        }
    }

    // validating participation
    if (empty($_POST['team']) || $_POST['team'] == 'undefined') {
        $response['teamErr'] = 'Required!';
        $flag = 1;
    } else {
        $team = mysqli_real_escape_string($conn, $_POST['team']);
        if ($team == "Team") {
            if (empty($_POST['teamSize'])) {
                $response['teamSizeErr'] = 'Required!';
                $flag = 1;
            } else {
                $teamSize = mysqli_real_escape_string($conn, $_POST['teamSize']);
            }
        }
    }

    // validating platform
    if (empty($_POST['platform']) || $_POST['platform'] == 'undefined') {
        $response['platformErr'] = 'Required!';
        $flag = 1;
    } else {
        $platform = mysqli_real_escape_string($conn, $_POST['platform']);
    }

    if (empty($_POST['location'])) {
        $response['locationErr'] = "Required!";
        $flag = 1;
    } else {
        $location = mysqli_real_escape_string($conn, $_POST['location']);
    }

    // validating approval
    if (empty($_POST['approval']) || $_POST['approval'] == 'undefined') {
        $response['approvalErr'] = 'Required!';
        $flag = 1;
    } else {
        $approval = mysqli_real_escape_string($conn, $_POST['approval']);
    }

    // validating participation
    if (empty($_POST['wheretoperform']) || $_POST['wheretoperform'] == 'undefined') {
        $response['wheretoperformErr'] = 'Required!';
        $flag = 1;
    } else {
        $wheretoperform = mysqli_real_escape_string($conn, $_POST['wheretoperform']);
        if ($wheretoperform == "Outside") {
            if (empty($_POST['external_link'])) {
                $response['external_linkErr'] = 'Required!';
                $flag = 1;
            } else {
                $externalLink = mysqli_real_escape_string($conn, $_POST['external_link']);
            }
        }
    }


    // if it pass every validation then push into db & store files
    if ($flag == 0) {


        // replacing images
        $getImg = "SELECT `logo`, `banner`, `thumbnail`, `token` FROM `activities` WHERE `id` = '$taskid'";
        $getImg_row = mysqli_fetch_assoc(mysqli_query($conn, $getImg));
        $token = $getImg_row['token'] == "" ? sha1(time()) : $getImg_row['token'];
        if ($filename != "" and $filename1 != "" and $filename2 != "") {

            $logoLocation = $getImg_row['logo'];
            compress_image($_FILES['activityLogo']['tmp_name'], $logoLocation, 50);
            $bannerLocation = $getImg_row['banner'];
            compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 50);
            $thumbnailLocation = $getImg_row['thumbnail'];
            compress_image($_FILES['thumbnailImage']['tmp_name'], $thumbnailLocation, 50);
            $sql = "UPDATE `activities` SET `title`='$title',`logo`='$logoLocation',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`banner`='$bannerLocation',`thumbnail`='$thumbnailLocation',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        } else if ($filename != "" and $filename1 != "") {

            $logoLocation = $getImg_row['logo'];
            compress_image($_FILES['activityLogo']['tmp_name'], $logoLocation, 50);
            $bannerLocation = $getImg_row['banner'];
            compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 50);
            $sql = "UPDATE `activities` SET `title`='$title',`logo`='$logoLocation',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`banner`='$bannerLocation',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        } else if ($filename != "" and $filename1 != "") {

            $logoLocation = $getImg_row['logo'];
            compress_image($_FILES['activityLogo']['tmp_name'], $logoLocation, 50);
            $thumbnailLocation = $getImg_row['thumbnail'];
            compress_image($_FILES['thumbnailImage']['tmp_name'], $thumbnailLocation, 50);
            $sql = "UPDATE `activities` SET `title`='$title',`logo`='$logoLocation',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`thumbnail`='$thumbnailLocation',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        } else if ($filename1 != "" and $filename2 != "") {

            $bannerLocation = $getImg_row['banner'];
            compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 50);
            $thumbnailLocation = $getImg_row['thumbnail'];
            compress_image($_FILES['thumbnailImage']['tmp_name'], $thumbnailLocation, 50);
            $sql = "UPDATE `activities` SET `title`='$title',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`banner`='$bannerLocation',`thumbnail`='$thumbnailLocation',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        } else if ($filename != "") {

            $logoLocation = $getImg_row['logo'];
            compress_image($_FILES['activityLogo']['tmp_name'], $logoLocation, 50);
            $sql = "UPDATE `activities` SET `title`='$title',`logo`='$logoLocation',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        } else if ($filename1 != "") {

            $bannerLocation = $getImg_row['banner'];
            compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 50);
            $sql = "UPDATE `activities` SET `title`='$title',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`banner`='$bannerLocation',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        } else if ($filename2 != "") {

            $thumbnailLocation = $getImg_row['thumbnail'];
            compress_image($_FILES['thumbnailImage']['tmp_name'], $thumbnailLocation, 50);
            $sql = "UPDATE `activities` SET `title`='$title',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`thumbnail`='$thumbnailLocation',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        } else {
            $sql = "UPDATE `activities` SET `title`='$title',`category`='$category',`organizer`='$organizer',`about_activity`='$about',`startDate`='$startDate',`endDate`='$endDate',`time`='$time',`organizername1`='$organizername1',`organizerno1`='$organizerno1',`organizername2`='$organizername2',`organizerno2`='$organizerno2',`participation`='$participate',`rewards`='$rewards',`type`='$type',`amountPaid`='$paidAmount',`team`='$team',`teamSize`='$teamSize',`platform`='$platform',`location`='$location', `approval` = '$approval', `external_link`='$externalLink', `token` = '$token' WHERE `id` = '$taskid'";
        }

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $response['success'] = true;
        }
    }


    echo json_encode($response);
}


// * ====================================
// * READ APPLICATIONS
// * ====================================
if (!empty($_POST['readApplicarion']) && !empty($_POST['applicationId'])) {
    $applicationId = $_POST['applicationId'];
    $approval = $_POST['approval'];
    $data = "
        <div class='table-responsive'>
            <table class='table table-sm table-striped' id='myTable' width='100%' style='font-size: 0.8rem'>
                <thead>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td><b>Name</b></td></td>
                    <td><b>Email</b></td></td>
                    <td><b>Mobile</b></td></td>
                    <td><b>State</b></td></td>
                    <td><b>City</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Status</b></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td style='max-width:20px'><b>Sr No</b></td>
                    <td><b>Name</b></td></td>
                    <td><b>Email</b></td></td>
                    <td><b>Mobile</b></td></td>
                    <td><b>State</b></td></td>
                    <td><b>City</b></td></td>
                    <td><b>College</b></td></td>
                    <td><b>Status</b></td>
                    <td class='text-center'><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
    // sql query with inner join
    $sql = "SELECT * FROM `activity_applications` WHERE `activityid` = '$applicationId' AND `status` <> 'deleted'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data .= "
                    <tr>
                        <td class='text-center'>" . $number . "</td>
                        <td>" . $row['name'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['mobile'] . "</td>
                        <td>" . $row['state'] . "</td>
                        <td>" . $row['city'] . "</td>
                        <td>" . $row['college'] . "</td>
                        <td>" . $row['status'] . "</td>
                        <td class='d-flex justify-content-center p-2' style='height: 100%'>
                ";
            if ($approval == "Yes") {
                if ($row['status'] == 'Under Review' || $row['status'] == 'Rejected') {
                    $data .= "<button class='btn solid rounded btn-success activity-" . $row['id'] . "' id='approve" . $row['id'] . "' onclick='ApproveUser(" . $row['id'] . ")' data-toggle='tooltip' title='Allow student to perform activity'><i class='far fa-check'></i></button>";
                } else {
                    $data .= "<button class='btn solid rounded btn-warning activity-" . $row['id'] . "' id='disapprove" . $row['id'] . "' onclick='DisapproveUser(" . $row['id'] . ")' data-toggle='tooltip' title='Disable student to perform activity'><i class='far fa-times'></i></button>";
                }
            }
            $data .= "<button class='btn solid rounded btn-primary activity-" . $row['id'] . "' id='view" . $row['id'] . "' onclick='ViewUser(" . $row['id'] . ")' title='View Application' data-toggle='modal' data-target='#view-user-modal'><i class='far fa-eye'></i></button>
                          <button class='btn solid rounded btn-danger activity-" . $row['id'] . "' id='delete" . $row['id'] . "' onclick='DeleteUser(" . $row['id'] . ")' data-toggle='tooltip' title='Delete Application'><i class='far fa-trash'></i></button>
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
// * APPROVE APPLICATION
// * ====================================
if (isset($_POST['a_approveid'])) {
    $approveid = $_POST['a_approveid'];
    $sql = "UPDATE `activity_applications` SET `status`='Active' WHERE `id`= '$approveid'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}

// * ====================================
// * DISAPPROVE APPLICATION
// * ====================================
if (isset($_POST['a_disapproveid'])) {
    $disapproveid = $_POST['a_disapproveid'];
    $sql = "UPDATE `activity_applications` SET `status`='Rejected' WHERE `id`= '$disapproveid'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}

// * ====================================
// * DELETE APPLICATION
// * ====================================
if (isset($_POST['a_deleteid'])) {
    $deleteid = $_POST['a_deleteid'];
    $sql = "DELETE FROM `activity_applications` WHERE `id`= '$deleteid'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}

// * ====================================
// * VIEW USER
// * ====================================
if (isset($_POST['userinfo'])) {
    $id = $_POST['userinfo'];
    $response = array();

    $sql = "SELECT * FROM `activity_applications` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $members = json_decode($row['members']);
    $row['members'] = $members;

    echo json_encode($row);
}
