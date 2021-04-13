<?php

    include_once "./db.php";

    // getting session variables
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];

    extract($_POST);

    // * ====================================
    // * ADD ACTIVITY
    // * ====================================
    if(isset($_POST['addBanner']) AND $_SERVER['REQUEST_METHOD'] == 'POST'){
        $response = array();
        $response['success'] = false; 
        $flag = 0;
        $url = "";
        // validating activity title
        if(empty($_POST['bannerurl'])){
            $response['bannerurlErr'] = "Required!";
            $flag = 1;
        }else{
            $bannerurl = mysqli_real_escape_string($conn, $_POST['bannerurl']);
            if (!filter_var($bannerurl, FILTER_VALIDATE_URL)) {
               $response['bannerurlErr'] = "Invalid Location";
           }
        }

        // validating banner logo
        $imageFileType1 = $filename1 = "";
        if(!isset($_FILES['bannerImage']['name'])){
            $response['bannerImageErr'] = "Upload banner image(1400px * 300px)";
            $flag = 1;
        }else{
            /* Getting file name */
            $filename1 = $_FILES['bannerImage']['name'];
            $logoLocation1 = "../media/slider/".$filename1;
            $imageFileType1 = pathinfo($logoLocation1,PATHINFO_EXTENSION);
            /* Valid Extensions */
            $valid_extensions = array("jpg","jpeg","png","JPG","JPEG","PNG");
            /* Check file extension */
            if(!in_array(strtolower($imageFileType1),$valid_extensions) ) {
                $uploadOk = 0;
                $response['bannerImageErr'] = "Unable to upload file. Invalid image format";
                $flag = 1;
            }
        }

        // if it pass every validation then push into db & store files
        if($flag == 0){
            
            // create directory
            $foldertimestamp = round(microtime(true));
            // copying gig logo
            $bannerLocation = '../media/slider/'. $foldertimestamp.'.jpg';
            compress_image($_FILES['bannerImage']['tmp_name'], $bannerLocation, 80);

            
            $sql = "INSERT INTO `dashboard_slider`(`banner`, `url`, `status`) 
                     VALUES ('$bannerLocation', '$bannerurl', 'Not Active')";
            $result = mysqli_query($conn, $sql);
            if($result){
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