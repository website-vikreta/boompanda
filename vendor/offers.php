<?php
    include_once "../dashboard/php/db.php";

    // sessions
    if(isset($_GET['type']) && $_GET['type'] == 'vendor'){
        if(isset($_SESSION['userType']) && $_SESSION['userType'] == 'superadmin'){
            if(isset($_GET['offerid'])){
                $offerid = $_GET['offerid'];
                $_SESSION['offerid'] = $offerid;
                if(mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `offers` WHERE `id` = '$offerid'")) > 0){
                    echo "true1";
                }
            }
        }else{
            if(isset($_SESSION['vendor_username']) and isset($_SESSION['vendor_password'])){
                $username = $_SESSION['vendor_username']; 
                $password = $_SESSION['vendor_password'];
                $sql = "SELECT * FROM `offers` WHERE `username` = '$username' AND `password` = '$password' ";
                $result = mysqli_query($conn, $sql);
                $_SESSION['offerid'] = mysqli_fetch_assoc($result)['id'];
                if($result){
                    echo 'true';
                }else{
                    echo "false";
                }
            }else{
                echo "false";
            }
        }
    }
    
    // vendor login
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){

        $username = $password = "";
        $flag = 0;
        $response = array();
        $array['success'] = false;
        
        // ==================================================
        // username validations
        // ==================================================
        if(empty($_POST['username'])){
            $response['usernameErr'] = "Enter username";
            $flag = 1;
        }else{
            $username = mysqli_real_escape_string($conn, $_POST['username']);
        }

        // ==================================================
        // password validations
        // ==================================================
        if(empty($_POST['password'])){
            $response['passwordErr'] = "Enter password";
            $flag = 1;
        }else{
            $password = mysqli_real_escape_string($conn, $_POST['password']);
        }

        // ==================================================
        // if no error check in db
        // ==================================================
        if($flag == 0){
            $sql = "SELECT * FROM `offers` WHERE `username` = '$username' OR `password` = '$password'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_assoc($result);
                $_SESSION['vendor_username'] = $username;
                $_SESSION['vendor_password'] = $password;
                $response['success'] = true;                
            }else if(mysqli_num_rows($result) < 1){
                $response['serverErr'] = "Invalid email or password!";
            }else{
                $response['serverErr'] = "Please contact admins. We encounter some issue.";
            }
        }
        
        echo json_encode($response);
    }

    // read records
    if(!empty($_POST['readrecord'])){
        $offerid = $_SESSION['offerid'];
        $data = "
        <div class='table-responsive-lg'>
            <table class='table table-sm table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td><b>Student Name</b></td>
                    <td><b>Email</b></td>
                    <td class='text-center'><b>Coupons Distributed</b></td>
                    <td class='text-center'><b>Coupons Redeem</b></td>
                    <td class='text-center'><b>Recent date of redeem</b></td>
                </thead>
                <tfoot>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td><b>Student Name</b></td>
                    <td><b>Email</b></td>
                    <td class='text-center'><b>Coupons Distributed</b></td>
                    <td class='text-center'><b>Coupons Redeem</b></td>
                    <td class='text-center'><b>Recent date of redeem</b></td>
                </tfoot>
                <tbody>
        ";
        $sql = "SELECT * FROM `offer_applications` WHERE `offerid` = '$offerid'";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $last = array_slice(explode(',',$row['dateOfRedeem']), -2, 1);
                $u = "SELECT `uid` FROM `user_info` WHERE `email` = '".$row['email']."' AND `userType` = '".$row['userType']."'";
                $row['coupon'] = mysqli_fetch_assoc(mysqli_query($conn, $u))['uid'];
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td class=''>".$row['name']."</td>
                        <td class=''>".$row['email']."</td>
                        <td class='text-center poppins'>".$row['total_redeem']."</td>
                        <td class='text-center poppins'>".$row['user_redeem']."</td>
                        <td class='text-center poppins font-weight-bold'>".$last[0]."</td>
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

    // read offer info
    if(isset($_POST['offerid'])){
        $offerid = $_SESSION['offerid'];
        $sql = "SELECT * FROM `offers` WHERE `id` = '$offerid'";
        echo json_encode(mysqli_fetch_assoc(mysqli_query($conn, $sql)));
    }

    // read statastics
    if(isset($_POST['readStat'])){
        $offerid = $_SESSION['offerid'];
        $date = date('d-m-Y');
        $sql = "SELECT SUM(`total_redeem`) AS `total`, 
                SUM(`user_redeem`) AS `redeem`,
                SUM(ROUND((LENGTH(`dateOfRedeem`) - LENGTH(REPLACE(`dateOfRedeem`, '$date', ''))) / LENGTH('$date'))) AS `today` 
                FROM `offer_applications` WHERE `offerid` = '$offerid'";
        echo json_encode(mysqli_fetch_assoc(mysqli_query($conn, $sql)));
    }

    // redeem coupon
    if(isset($_POST['coupon'])){
        $coupon = $_POST['coupon'];
        $date = date("d-m-Y");
        $response = array();
        $response['success'] = false;
        $flag = 0; $u_email = $u_type = "";
        $offerid = $_SESSION['offerid'];

        // check for blank coupon
        if($coupon == ""){
            $response['message'] = "Please enter coupon!";
            $flag = 1;
        }else{
            // get username & userType
            $sql = "SELECT * FROM `user_info` WHERE `uid` = '$coupon'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) != 1){
                $response['message'] = "Invalid Coupon!";
                $flag = 1;
            }else{
                $row = mysqli_fetch_assoc($result);
                $u_email = $row['email'];
                $u_type = $row['userType'];

                // getting info from offers table
                $sql = "SELECT * FROM `offer_applications` WHERE `email` = '$u_email' AND `userType` = '$u_type' AND `offerid` = '$offerid'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) != 1){
                    $response['message'] = "User doesn't apply for the offer";
                    $flag = 1;
                }else{
                    $row = mysqli_fetch_assoc($result);
                    if($row['total_redeem'] <= $row['user_redeem']){
                        $response['message'] = "User already redeem the coupon";
                        $flag = 1;
                    }
                }
            }
        }

        if($flag == 0){
            // fetching offer details
            $sql = "SELECT * FROM `offers` WHERE `id` = '$offerid'";
            $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
            $title = 'Coupon cashback for - '.$row['title'];
            // getting amount
            $amount = 0;
            if($row['offer_type'] == 'paid' || $row['offer_type'] == 'free'){
                if($row['cashback_type'] == 'rupees'){
                    $amount = $row['cashback'] * 10;
                }else if($row['cashback_type'] == 'percentage'){
                    $amount = round((($row['cashback']/100)*$row['amount_paid'])*10);
                }
            }
            // $amount = ($row['cashback'] != "") && ($row['offer_type'] == 'paid') ? (($row['cashback']/100)*$row['amount_paid'])*10 : 0;
            $tid = 'credit_'.generateRandomString(14);
            // updating offer application
            $sql = "UPDATE `offer_applications` SET `user_redeem`=`user_redeem` + 1,`dateOfRedeem`= CONCAT(`dateOfRedeem` , '$date' , ',') WHERE `email` = '$u_email' AND `userType` = '$u_type' AND `offerid` = '$offerid'";
            $result = mysqli_query($conn, $sql);
            if($amount != 0){
                // creating transaction entry
                $sql = "INSERT INTO `transactions`(`email`, `userType`, `transactionID`, `description`, `date`, `amount`, `action`, `status`) 
                        VALUES ('$u_email', '$u_type', '$tid', '$title', '$date', '$amount - Boomcoins', 'credit', 'success')";
                $result1 = mysqli_query($conn, $sql);
                // adding amount in wallet
                $sql = "UPDATE `wallet` SET `balance`=`balance` + $amount,`total_earning`=`total_earning` + $amount WHERE `email` = '$u_email' AND `userType` = '$u_type'";
                $result2 = mysqli_query($conn, $sql);
            }else{
                $result1 = $result2 = 1;
            }

            if($result && $result2 && $result1){
                $response['success'] = true;
            }else{
                $response['message'] = "Something went wrong";
            }
        }

        echo json_encode($response);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
