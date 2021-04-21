<?php

include_once "./db.php";

// getting session variables
$email = $_SESSION['email'];
$userType = $_SESSION['userType'];

extract($_POST);

// * ====================================
// * ADD ACTIVITY
// * ====================================
if (isset($_POST['addBanner']) and $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array();
    $response['success'] = false;
    $flag = 0;
    $url = "";
    // validating activity title
    if (empty($_POST['bannerurl'])) {
        $response['bannerurlErr'] = "Required!";
        $flag = 1;
    } else {
        $bannerurl = mysqli_real_escape_string($conn, $_POST['bannerurl']);
        if (!filter_var($bannerurl, FILTER_VALIDATE_URL)) {
            $response['bannerurlErr'] = "Invalid Location";
        }
    }

    // validating banner logo
    $imageFileType1 = $filename1 = "";
    if (!isset($_FILES['bannerImage']['name'])) {
        $response['bannerImageErr'] = "Upload banner image(1400px * 300px)";
        $flag = 1;
    } else {
        /* Getting file name */
        $filename1 = $_FILES['bannerImage']['name'];
        $logoLocation1 = "../media/slider/" . $filename1;
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

    // if it pass every validation then push into db & store files
    if ($flag == 0) {

        // create directory
        $foldertimestamp = round(microtime(true));
        // copying gig logo
        $bannerLocation = '../media/slider/' . $foldertimestamp . '.jpg';
        compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 80);


        $sql = "INSERT INTO `dashboard_slider`(`banner`, `url`, `status`) 
                     VALUES ('$bannerLocation', '$bannerurl', 'Not Active')";
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
            <div class='table-responsive-xl'>
            <table class='table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td><b>Banner</b></td></td>
                    <td><b>URL</b></td></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td><b>Banner</b></td></td>
                    <td><b>URL</b></td></td>
                    <td class='text-center'><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
    // sql query with inner join
    $sql = "SELECT * FROM `dashboard_slider` ORDER BY `id` DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data .= "
                    <tr>
                        <td class='text-center'>" . $number . "</td>
                        <td class='text-center' style='width:500px'><img src='" . substr($row['banner'], 1) . "' class='img-fluid' style='width:100%'></td>
                        <td>" . $row['url'] . "</td>
                ";
            $data .= "                        
                    <td class='d-flex justify-content-end'>
                        <button class='btn solid rounded btn-secondary activity-" . $row['id'] . "' id='edit" . $row['id'] . "' onclick='EditBanner(" . $row['id'] . ")' title='Edit' data-toggle='modal' data-target='#edit-banner-modal'><i class='far fa-edit'></i></button>
                        <button class='btn solid rounded btn-danger activity-" . $row['id'] . "' id='delete" . $row['id'] . "' onclick='DeleteBanner(" . $row['id'] . ")' data-toggle='tooltip' title='Delete Banner'><i class='far fa-trash'></i></button>
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
// * DELETE BANNER
// * ====================================
if (isset($_POST['deleteid'])) {
    $deleteid = $_POST['deleteid'];

    // deleting file
    $sql = "SELECT * FROM `dashboard_slider` WHERE `id` = '$deleteid'";
    $r = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $filename = $r['banner'];
    if (file_exists($filename)) {
        unlink($filename);
    }

    $sql = "DELETE FROM `dashboard_slider` WHERE `id` = '$deleteid'";
    $result = mysqli_query($conn, $sql);
    // delete application remaining
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}

// * ====================================
// * EDIT BANNER
// * ====================================
if (isset($_POST['editid'])) {
    $editid = $_POST['editid'];
    $sql = "SELECT * FROM `dashboard_slider` WHERE `id` = '$editid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
}

if (isset($_POST['editBanner']) and $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array();
    $response['success'] = false;
    $flag = 0;
    $bannerurl = "";
    $editid = $_POST['editbannerid'];
    // validating activity title
    if (empty($_POST['bannerurl'])) {
        $response['bannerurlErr'] = "Required!";
        $flag = 1;
    } else {
        $bannerurl = mysqli_real_escape_string($conn, $_POST['bannerurl']);
        if (!filter_var($bannerurl, FILTER_VALIDATE_URL)) {
            $response['bannerurlErr'] = "Invalid Location";
        }
    }

    // validating banner logo
    $imageFileType1 = $filename = "";
    if (isset($_FILES['bannerImage']['name'])) {
        /* Getting file name */
        $filename = $_FILES['bannerImage']['name'];
        $logoLocation1 = "../media/slider/" . $filename;
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

    // if it pass every validation then push into db & store files
    if ($flag == 0) {

        if ($filename != "") {
            // unlinking previous image
            $sql = "SELECT * FROM `dashboard_slider` WHERE `id` = '$editid'";
            $r = mysqli_fetch_assoc(mysqli_query($conn, $sql));
            $filename123 = $r['banner'];
            if (file_exists($filename123)) {
                unlink($filename123);
            }

            // create directory
            $foldertimestamp = round(microtime(true));
            // copying gig logo
            $bannerLocation = '../media/slider/' . $foldertimestamp . '.jpg';
            compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 80);
            $sql = "UPDATE `dashboard_slider` SET `banner`='$bannerLocation',`url`='$bannerurl' WHERE `id` = '$editid'";
        } else {
            $sql = "UPDATE `dashboard_slider` SET `url`='$bannerurl' WHERE `id` = '$editid'";
        }

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $response['success'] = true;
        }
    }


    echo json_encode($response);
}
