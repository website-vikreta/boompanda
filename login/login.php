<?php

//index.php

//Include Configuration File
include_once "./php/db.php";
include('config.php');

if(!isset($_SESSION['access_token']))
{
    header('location: '.$google_client->createAuthUrl());
}else{
    session_destroy();
    header('location: '.$google_client->createAuthUrl());
}

if(isset($_GET["code"]))
{
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
    if(!isset($token['error']))
    {
        $email = $profile = "";
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];
        $token_val = $token['access_token'];
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        // if(!empty($data['given_name']))
        // {
        //     $fname = $data['given_name'];
        // }
        // if(!empty($data['family_name']))
        // {
        //     $lname = $data['family_name'];
        // }
        if(!empty($data['email']))
        {
            $email = $data['email'];
        }
        if(!empty($data['name']))
        {
            $name = $data['name'];
        }
        if(!empty($data['picture']))
        {
            $picture = $data['picture'];
        }

        $sql = "SELECT `id` FROM `user` WHERE `email` = '$email' AND `userType` = 'google' ";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) < 1){

            // generating unique number
            $uid = 0;
            while(1){
                $uid = mt_rand(100000, 999999)                ;
                $sql = "SELECT * FROM `user_info` WHERE `uid` = '$uid'";
                if(mysqli_num_rows(mysqli_query($conn, $sql)))
                    continue;
                else
                    break;
            }

            $sql = "INSERT INTO `user`(`name`, `email`, `profile`, `userType`, `status`, `token`) VALUES ('$name', '$email','$picture', 'google', 'verified', '$token_val')";
            $sql1 = "INSERT INTO `user_info` (`email`, `userType`, `uid`) VALUES('$email', 'google', '$uid')";
            $sql2 = "INSERT INTO `wallet` (`email`, `userType`) VALUES('$email', 'google')";
            $res = mysqli_query($conn, $sql);
            $res1 = mysqli_query($conn, $sql1);
            $res2 = mysqli_query($conn, $sql2);
        }
        $_SESSION['email'] = $email;
        $_SESSION['userType'] = "google";
        header('location: ../dashboard/index.html');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Boompanda</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon-16x16.png">
    <link rel="shortcut icon" href="../assets/favicon.ico">
    <link rel="manifest" href="../assets/site.webmanifest">
</head>
<body>
    
</body>
</html>