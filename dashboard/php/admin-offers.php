<?php

    include_once "./db.php";

    // getting session variables
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];

    extract($_POST);

    // * ====================================
    // * ADD OFFER
    // * ====================================
    if(isset($_POST['addOffer']) AND $_SERVER['REQUEST_METHOD'] == 'POST'){
        $response = array();
        $response['success'] = false; 
        $flag = 0;
        $title = $category = $brand = $about = $redeemCount = $endDate = "";
        $participate = $storeType = $cashback = $offerType = $location = $college_name = "";
        $paidAmount = 0;

        // validating offer logo
        $imageFileType = $filename = "";
        if(!isset($_FILES['offerLogo']['name'])){
            $response['offerLogoErr'] = "Upload brand's logo";
            $flag = 1;
        }else{
            /* Getting file name */
            $filename = $_FILES['offerLogo']['name'];
            $logoLocation = "../media/offers/".$filename;
            $imageFileType = pathinfo($logoLocation,PATHINFO_EXTENSION);
            /* Valid Extensions */
            $valid_extensions = array("jpg","jpeg","png","JPG","JPEG","PNG");
            /* Check file extension */
            if(!in_array(strtolower($imageFileType),$valid_extensions) ) {
                $uploadOk = 0;
                $response['offerLogoErr'] = "Unable to upload file. Invalid image format";
                $flag = 1;
            }
        }

        // validating offer title
        if(empty($_POST['title'])){
            $response['titleErr'] = "Required!";
            $flag = 1;
        }else{
            $title = mysqli_real_escape_string($conn, $_POST['title']);
        }

        // validating offer category
        if($_POST['category'] == "-1"){
            $response['categoryErr'] = "Select one option";
            $flag = 1;
        }else{
            $category = mysqli_real_escape_string($conn, $_POST['category']);
        }

        // validating brand's name
        if(empty($_POST['brandName'])){
            $response['brandNameErr'] = 'Required!';
            $flag = 1;
        }else{
            $brand = mysqli_real_escape_string($conn, $_POST['brandName']);
            if(preg_match("/[^A-Za-z0-9 '-]/", $brand)){
                $response['brandNameErr'] = "Must be alphanumeric";
                $flag = 1;
            }
        }

        // validating about offer
        if(empty($_POST['aboutOffer'])){
            $response['aboutOfferErr'] = "Required!";
            $flag = 1;
        }else{
            $about = mysqli_real_escape_string($conn, $_POST['aboutOffer']);
        }

        // validating end date
        if(empty($_POST['endDate'])){
            $response['endDateErr'] = 'Required!';
            $flag = 1;
        }else{
            $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
        }

        // validating redeem count
        if(empty($_POST['use'])){
            $response['useErr'] = 'Required!';
            $flag = 1;
        }else{
            $redeemCount = mysqli_real_escape_string($conn, $_POST['use']);
            if(preg_match("/[^0-9'-]/", $redeemCount)){
                $response['useErr'] = "Must be numeric";
                $flag = 1;
            }
        }

        // validating who can avain
        if(empty($_POST['participate'])){
            $response['participateErr'] = "Required!";
            $flag = 1;
        }else{
            $participate = mysqli_real_escape_string($conn, $_POST['participate']);
        }
        

        // validating offer type
        if(empty($_POST['offerType']) || $_POST['offerType'] == 'undefined'){
            $response['typeErr'] = 'Required!';
            $flag = 1;
        }else{
            $type = mysqli_real_escape_string($conn, $_POST['offerType']);
            if($type == "paid"){
                if(empty($_POST['paidAmount'])){
                    $response['paidAmountErr'] = 'Required!';
                    $flag = 1;
                }else{
                    $paidAmount = mysqli_real_escape_string($conn, $_POST['paidAmount']);
                    if(preg_match("/[^0-9'-]/", $paidAmount)){
                        $response['paidAmountErr'] = "Must be numeric";
                        $flag = 1;
                    }
                }
            }
        }

        // validating store type
        if(empty($_POST['storeType']) || $_POST['storeType'] == 'undefined'){
            $response['storeTypeErr'] = 'Required!';
            $flag = 1;
        }else{
            $storeType = mysqli_real_escape_string($conn, $_POST['storeType']);
        }

        // validating location
        if(empty($_POST['location'])){
            $response['locationErr'] = "Required!";
            $flag = 1;
        }else{
            $location = mysqli_real_escape_string($conn, $_POST['location']);
        }

        // validating college
        $college_name = mysqli_real_escape_string($conn, $_POST['college']);
        $college_name = ($college_name == '-- Select College --') ? "" : $college_name;

        // validating cashback
        if(empty($_POST['cashback']) || $_POST['cashback'] == 'undefined'){
            $response['cashbackErr'] = 'Required!';
            $flag = 1;
        }else{
            $cashback = mysqli_real_escape_string($conn, $_POST['cashback']);
            if(preg_match("/[^0-9'-]/", $cashback)){
                $response['cashbackErr'] = "Must be numeric";
                $flag = 1;
            }
        }

        // if it pass every validation then push into db & store files
        if($flag == 0){
            
            // create directory
            $foldertimestamp = round(microtime(true));
            if(!file_exists("../media/offers/".$foldertimestamp)){
                mkdir("../media/offers/" . $foldertimestamp, 0777);
            }
            // copying offer logo
            $logoLocation = '../media/offers/'. $foldertimestamp.'/'.$filename;
                if(compress_image($_FILES['offerLogo']['tmp_name'], $logoLocation, 50)){
                // creating login credentials for vendor
                $username = substr(preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['brandName']), 0, 10);
                $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $password = substr(str_shuffle($str_result), 0, 6);
                
                $sql = "INSERT INTO `offers`(`title`, `logo`, `brand`, `category`, `about`, `end_date`, `redeem_count`, `avail`, `store_type`, `offer_type`, `amount_paid`, `cashback`, `location`, `campus`, `username`, `password`, `status`) 
                        VALUES ('$title', '$logoLocation', '$brand', '$category', '$about', '$endDate', '$redeemCount', '$participate', '$storeType', '$type', '$paidAmount', '$cashback', '$location', '$college_name', '$username', '$password', 'Not Active')";
                $result = mysqli_query($conn, $sql);
                if($result){
                    $response['success'] = true;
                }
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
    if(!empty($_POST['readrecord'])){

        $data = "
            <div class='table-responsive'>
            <table class='table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td style='width:10px'><b>Offer Logo</b></td>
                    <td><b>Title</b></td></td>
                    <td><b>Brand</b></td></td>
                    <td><b>End Date</b></td></td>
                    <td><b>Number of Applications</b></td></td>
                    <td><b>Status</b></td></td>
                    <td class='text-center'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td style='width:10px'><b>Offer Logo</b></td>
                    <td><b>Title</b></td></td>
                    <td><b>Brand</b></td></td>
                    <td><b>End Date</b></td></td>
                    <td><b>Number of Applications</b></td></td>
                    <td><b>Status</b></td></td>
                    <td class='text-center'><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT * FROM `offers`";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td class='text-center'><img src='".substr($row['logo'], 1)."' class='img-fluid' style='height: 40px'></td>
                        <td>".$row['title']."</td>
                        <td>".$row['brand']."</td>
                        <td class='poppins'>".$row['end_date']."</td>
                        <td class='text-center poppins'>".$row['noOfApplication']."</td>
                        <td class='text-center'>".$row['status']."</td>
                        <td class='d-flex justify-content-end'>
                            <button class='btn solid rounded btn-info activity-".$row['id']."' title='View complete info' id='activity".$row['id']."' onclick='viewOffer(".$row['id'].")' data-toggle='modal' data-target='#view-offer-modal'><i class='far fa-eye'></i></button>
                ";
                if($row['status'] == "Not Active"){
                    $data .= "
                    <button class='btn solid rounded btn-success activity-".$row['id']."' id='approve".$row['id']."' onclick='activeOffer(".$row['id'].")' data-toggle='tooltip' title='Active'><i class='far fa-check'></i></button>
                    ";
                }else if($row['status'] == "Active"){
                    $data .= "
                    <button class='btn solid rounded btn-warning activity-".$row['id']."' id='disapprove".$row['id']."' onclick='deactiveOffer(".$row['id'].")' data-toggle='tooltip' title='Not Active'><i class='far fa-times'></i></button>
                    ";
                }
                $data .= "                        
                        <a target='_BLANK' href='../vendor/?offerid=".$row['id']."' class='btn solid rounded btn-primary activity-".$row['id']."' title='View applications'><i class='far fa-tasks'></i></a>
                        <button class='btn solid rounded btn-secondary activity-".$row['id']."' id='edit".$row['id']."' onclick='EditOffer(".$row['id'].")' title='Edit' data-toggle='modal' data-target='#edit-offer-modal'><i class='far fa-edit'></i></button>
                        <button class='btn solid rounded btn-danger activity-".$row['id']."' id='delete".$row['id']."' onclick='DeleteOffer(".$row['id'].")' data-toggle='tooltip' title='Delete Activity'><i class='far fa-trash'></i></button>
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
    // * ACTIVE OFFER
    // * ====================================
    if(isset($_POST['approveid'])){
        $approveid = $_POST['approveid'];
        $sql = "UPDATE `offers` SET `status`='Active' WHERE `id`= '$approveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }
    // * ====================================
    // * INACTIVE OFFER
    // * ====================================
    if(isset($_POST['disapproveid'])){
        $disapproveid = $_POST['disapproveid'];
        $sql = "UPDATE `offers` SET `status`='Not Active' WHERE `id`= '$disapproveid'";
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
        $sql = "DELETE FROM `offers` WHERE `id` = '$deleteid'";
        $result = mysqli_query($conn, $sql);
        // delete application remaining
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * READ SINGLE RECORD
    // * ====================================
    if(isset($_POST['offerid'])){
        $activofferidiofferidtyid = $_POST['offerid'];
        $sql = "SELECT * FROM `offers` WHERE `id` = '$offerid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $row['userType'] = $userType;
        echo json_encode($row);
    }
    if(isset($_POST['editid'])){
        $editid = $_POST['editid'];
        $sql = "SELECT * FROM `offers` WHERE `id` = '$editid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }

    // * ====================================
    // * EDIT OFFER
    // * ====================================
    if(isset($_POST['editOffer']) AND $_SERVER['REQUEST_METHOD'] == 'POST'){
        $response = array();
        $response['success'] = false; 
        $flag = 0;
        $title = $category = $brand = $about = $redeemCount = $endDate = "";
        $participate = $storeType = $cashback = $offerType = $location = "";
        $paidAmount = 0;
        $taskid = $_POST['taskid'];

        // validating offer logo
        $imageFileType = $filename = "";
        if(isset($_FILES['offerLogo']['name'])){
            /* Getting file name */
            $filename = $_FILES['offerLogo']['name'];
            $logoLocation = "../media/offers/".$filename;
            $imageFileType = pathinfo($logoLocation,PATHINFO_EXTENSION);
            /* Valid Extensions */
            $valid_extensions = array("jpg","jpeg","png","JPG","JPEG","PNG");
            /* Check file extension */
            if(!in_array(strtolower($imageFileType),$valid_extensions) ) {
                $uploadOk = 0;
                $response['offerLogoErr'] = "Unable to upload file. Invalid image format";
                $flag = 1;
            }
        }

        // validating offer title
        if(empty($_POST['title'])){
            $response['titleErr'] = "Required!";
            $flag = 1;
        }else{
            $title = mysqli_real_escape_string($conn, $_POST['title']);
        }

        // validating offer category
        if($_POST['category'] == "-1"){
            $response['categoryErr'] = "Select one option";
            $flag = 1;
        }else{
            $category = mysqli_real_escape_string($conn, $_POST['category']);
        }

        // validating brand's name
        if(empty($_POST['brandName'])){
            $response['brandNameErr'] = 'Required!';
            $flag = 1;
        }else{
            $brand = mysqli_real_escape_string($conn, $_POST['brandName']);
            if(preg_match("/[^A-Za-z0-9 '-]/", $brand)){
                $response['brandNameErr'] = "Must be alphanumeric";
                $flag = 1;
            }
        }

        // validating about offer
        if(empty($_POST['aboutOffer'])){
            $response['aboutOfferErr'] = "Required!";
            $flag = 1;
        }else{
            $about = mysqli_real_escape_string($conn, $_POST['aboutOffer']);
        }

        // validating end date
        if(empty($_POST['endDate'])){
            $response['endDateErr'] = 'Required!';
            $flag = 1;
        }else{
            $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
        }

        // validating redeem count
        if(empty($_POST['use'])){
            $response['useErr'] = 'Required!';
            $flag = 1;
        }else{
            $redeemCount = mysqli_real_escape_string($conn, $_POST['use']);
            if(preg_match("/[^0-9'-]/", $redeemCount)){
                $response['useErr'] = "Must be numeric";
                $flag = 1;
            }
        }

        // validating who can avain
        if(empty($_POST['participate'])){
            $response['participateErr'] = "Required!";
            $flag = 1;
        }else{
            $participate = mysqli_real_escape_string($conn, $_POST['participate']);
        }
        

        // validating offer type
        if(empty($_POST['offerType']) || $_POST['offerType'] == 'undefined'){
            $response['typeErr'] = 'Required!';
            $flag = 1;
        }else{
            $type = mysqli_real_escape_string($conn, $_POST['offerType']);
            if($type == "paid"){
                if(empty($_POST['paidAmount'])){
                    $response['paidAmountErr'] = 'Required!';
                    $flag = 1;
                }else{
                    $paidAmount = mysqli_real_escape_string($conn, $_POST['paidAmount']);
                    if(preg_match("/[^0-9'-]/", $paidAmount)){
                        $response['paidAmountErr'] = "Must be numeric";
                        $flag = 1;
                    }
                }
            }
        }

        // validating store type
        if(empty($_POST['storeType']) || $_POST['storeType'] == 'undefined'){
            $response['storeTypeErr'] = 'Required!';
            $flag = 1;
        }else{
            $storeType = mysqli_real_escape_string($conn, $_POST['storeType']);
        }

        // validating location
        if(empty($_POST['location'])){
            $response['locationErr'] = "Required!";
            $flag = 1;
        }else{
            $location = mysqli_real_escape_string($conn, $_POST['location']);
        }

        // validating cashback
        if(empty($_POST['cashback']) || $_POST['cashback'] == 'undefined'){
            $response['cashbackErr'] = 'Required!';
            $flag = 1;
        }else{
            $cashback = mysqli_real_escape_string($conn, $_POST['cashback']);
            if(preg_match("/[^0-9'-]/", $cashback)){
                $response['cashbackErr'] = "Must be numeric";
                $flag = 1;
            }
        }


        // if it pass every validation then push into db & store files
        if($flag == 0){
            // replacing images
            $getImg = "SELECT `logo` FROM `offers` WHERE `id` = '$taskid'";
            $getImg_row = mysqli_fetch_assoc(mysqli_query($conn, $getImg));
            if($filename != ""){
                $logoLocation = $getImg_row['logo'];
                compress_image($_FILES['offerLogo']['tmp_name'], $logoLocation, 50);
                $sql = "UPDATE `offers` SET `title`='$title',`logo`='$logoLocation',`brand`='$brand',`category`='$category',`about`='$about',`end_date`='$endDate',`redeem_count`='$redeemCount',`avail`='$participate',`store_type`='$storeType',`offer_type`='$type',`amount_paid`='$paidAmount',`cashback`='$cashback',`location`='$location' WHERE `id` = '$taskid'";
            }else{
                $sql = "UPDATE `offers` SET `title`='$title',`brand`='$brand',`category`='$category',`about`='$about',`end_date`='$endDate',`redeem_count`='$redeemCount',`avail`='$participate',`store_type`='$storeType',`offer_type`='$type',`amount_paid`='$paidAmount',`cashback`='$cashback',`location`='$location' WHERE `id` = '$taskid'";
            }
            
            $result = mysqli_query($conn, $sql);
            if($result){
                $response['success'] = true;
            }
        }


        echo json_encode($response);
    }