<?php
    include_once "./php/db.php";
    $email = $_SESSION['email'];
    $token = $_SESSION['token'];
    $sql = "SELECT * FROM `user` WHERE `email` = '$email' AND `token` = '$token'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify User</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center
        }

        .icon {
            max-width: 150px;
            margin: auto;
        }

        .icon img {
            width: 100%;
        }
    </style>
</head>

<body>

    <?php
        if($row['status'] == 'not verified'){
            $sql = "UPDATE `user` SET `status` = 'verified' WHERE `email` = '$email' AND `token` = '$token'";
            $result = mysqli_query($conn, $sql);
            if($result){
    ?>
    <div class="content">
        <div class="icon">
            <img src="./assets/success.gif" alt="">
        </div>
        <h1>Success</h1>
        <p>Your account is now verified.</p>
        <button href="./login.html" class="btn solid" onclick="window.location.href = './login.html'">Login</button>
    </div>
    <?php        }else{
                echo "something went wrong";
            }
        }else{
    ?>
    <div class="content">
        <div class="icon">
            <img src="./assets/success.gif" alt="">
        </div>
        <h1>Success</h1>
        <p>Your account is already verified.</p>
        <button href="./login.html" class="btn solid" onclick="window.location.href = './login.html'">Login</button>
    </div>
    <?php
        }
    ?>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="./js/verifyuser.js"></script>
</body>

</html>