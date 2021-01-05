<?php

    include_once "./db.php";

    // getting session variables
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $response = array();
        $response['success'] = false;
        $gigLogo = $title = $category = $name = $description = $startdate = $enddate = $complexity = "";
        $tutorialLink = $requirements = $completion = $interests = $apply = $boomcoins = "";
        $flag = 0;

        // validating gigLogo
        $imageFileType = $filename = "";
        if(!isset($_FILES['gigLogo']['name'])){
            $response['gigLogoErr'] = "Upload company's logo";
            $flag = 1;
        }else{
            /* Getting file name */
            $filename = $_FILES['gigLogo']['name'];
            $logoLocation = "../media/tasks/".$filename;
            $imageFileType = pathinfo($logoLocation,PATHINFO_EXTENSION);
            /* Valid Extensions */
            $valid_extensions = array("jpg","jpeg","png");
            /* Check file extension */
            if(!in_array(strtolower($imageFileType),$valid_extensions) ) {
                $uploadOk = 0;
                $response['gigLogoErr'] = "Unable to upload file. Invalid image format";
                $flag = 1;
            }
        }

        // validating gig title
        if(empty($_POST['gig-title'])){
            $response['gigTitleErr'] = "Required!";
            $flag = 1;
        }else{
            $title = mysqli_real_escape_string($conn, $_POST['gig-title']);
        }

        // validating gig category
        if($_POST['gig-category'] == "-1"){
            $response['gigCategoryErr'] = "Select one option";
            $flag = 1;
        }else{
            $category = mysqli_real_escape_string($conn, $_POST['gig-category']);
        }

        // validating company name
        if(empty($_POST['company-name'])){
            $response['companyNameErr'] = 'Required!';
            $flag = 1;
        }else{
            $name = mysqli_real_escape_string($conn, $_POST['company-name']);
            if(preg_match("/[^A-Za-z0-9 '-]/", $name)){
                $response['companyNameErr'] = "Must be alphanumeric";
                $flag = 1;
            }
        }

        // validating company's description
        if(empty($_POST['company-description'])){
            $response['companyDescriptionErr'] = "Required!";
            $flag = 1;
        }else{
            $description = mysqli_real_escape_string($conn, $_POST['company-description']);
        }

        // validating start date
        if(empty($_POST['start-date'])){
            $response['startDateErr'] = 'Required!';
            $flag = 1;
        }else{
            $startdate = mysqli_real_escape_string($conn, $_POST['start-date']);
        }

        // validating end date
        if(empty($_POST['end-date'])){
            $response['endDateErr'] = 'Required!';
            $flag = 1;
        }else{
            $enddate = mysqli_real_escape_string($conn, $_POST['end-date']);
        }

        // validating boom coins
        if(empty($_POST['boom-coins'])){
            $response['boomCoinsErr'] = 'Required!';
            $flag = 1;
        }else{
            $boomcoins = mysqli_real_escape_string($conn, $_POST['boom-coins']);
            if(!is_numeric($boomcoins)){
                $response['boom-coinsErr'] = 'Must be number';
                $flag = 1;
            }
        }

        // validating complexity
        if(empty($_POST['complexity'])){
            $response['complexityErr'] = 'Required!';
            $flag = 1;
        }else{
            $complexity = mysqli_real_escape_string($conn, $_POST['complexity']);
        }

        // tutorial link
        if(empty($_POST['tutorial-link'])){
            $response['tutorialLinkErr'] = 'Required!';
            $flag = 1;
        }else{
            $tutorialLink = mysqli_real_escape_string($conn, $_POST['tutorial-link']);
            if (!filter_var($tutorialLink, FILTER_VALIDATE_URL)) {
                $response['tutorialLinkErr'] = 'Invalid URL';
                $flag = 1;
            }
        }

        // validating sample proofs
        $no_files = 0;
        if (isset($_FILES['sample-proofs']) && !empty($_FILES['sample-proofs'])) {
            $no_files = count($_FILES["sample-proofs"]['name']); //counting total number of files for iterations
        } else {
            $response['sampleProofsErr'] = "Required!";
            $flag = 1;
        }

        // validating requirements
        if(empty($_POST['requirements'])){
            $response['requirementsErr'] = 'Required!';
            $flag = 1;
        }else{
            $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
        }

        // validating completion
        if(empty($_POST['completion'])){
            $response['completionErr'] = 'Required!';
            $flag = 1;
        }else{
            $completion = mysqli_real_escape_string($conn, $_POST['completion']);
        }

        // validating interets
        $interest_list = json_decode($_POST['interests']);
        if(sizeof($interest_list) > 0){
            if(sizeof($interest_list) < 2){
                $response['interestsErr'] = "Choose atleast 2 interest.";
                $flag = 1;
            }else{
                $interests = mysqli_real_escape_string($conn, implode(',', $interest_list));
            }
        }else{
            $response['interestsErr'] = 'Required!';
            $flag = 1;
        }

        // validating apply
        if(empty($_POST['apply'])){
            $response['applyErr'] = 'Required!';
            $flag = 1;
        }else{
            $apply = mysqli_real_escape_string($conn, $_POST['apply']);
        }


        // if it pass every validation then push into db & store files
        if($flag == 0){
            
            // create directory
            $foldertimestamp = round(microtime(true));
            if(!file_exists("../media/tasks/".$foldertimestamp)){
                mkdir("../media/tasks/" . $foldertimestamp, 0777);
                mkdir("../media/tasks/" . $foldertimestamp . "/submissions");
                mkdir("../media/tasks/" . $foldertimestamp . "/samples");
            }
            // copying gig logo
            $gigLogoLocation = '../media/tasks/'. $foldertimestamp.'/'.$filename;
            compress_image($_FILES['gigLogo']['tmp_name'], $gigLogoLocation, 50);
            // copying sample files
            $gigSampleLocation = '../media/tasks'.$foldertimestamp.'/samples/';
            for ($i = 0; $i < $no_files; $i++) {
                if ($_FILES["sample-proofs"]["error"][$i] > 0) {
                    $response['sampleProofsErr'] = "Error: " . $_FILES["sample-proofs"]["error"][$i] . "<br>";
                    $flag = 1;
                } else {
                    compress_image($_FILES["sample-proofs"]["tmp_name"][$i], "../media/tasks/" . $foldertimestamp . "/samples/".$_FILES["sample-proofs"]["name"][$i] , 50);
                }
            }

            if($flag == 0){
                $sql = "INSERT INTO `tasks`(`title`, `category`, `gigLogo`, `companyName`, `companyDescription`, `startDate`, `endDate`, `boomcoins`, `complexity`, `sampleProofs`, `tutorialLink`, `requirements`, `completion`, `interests`, `apply`, `status`) 
                        VALUES ('$title', '$category', '$gigLogoLocation', '$name', '$description', '$startdate', '$enddate', '$boomcoins', '$complexity', '$gigSampleLocation', '$tutorialLink', '$requirements', '$completion', '$interests', '$apply', `Not Active`)";
                $result = mysqli_query($conn, $sql);
                if($result){
                    $response['success'] = true;
                }
            }else{
                mkdir("../media/tasks/" . $foldertimestamp, 0777);
            }


        }



        // returning JSON output
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