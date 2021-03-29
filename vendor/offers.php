<?php
    include_once "../dashboard/php/db.php";

    // sessions
    if(isset($_GET['type']) && $_GET['type'] == 'vendor'){
        if(isset($_SESSION['userType']) && $_SESSION['userType'] == 'superadmin'){
            if(isset($_GET['offerid'])){
                $offerid = $_GET['offerid'];
                $_SESSION['offerid'] = $offerid;
                if(mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `offers` WHERE `id` = '$offerid'")) > 0){
                    echo "true";
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
                    <td class='text-center'><b>Coupon code</b></td>
                    <td class='text-center' style='width:150px'><b>Action</b></td>
                </thead>
                <tfoot>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td><b>Student Name</b></td>
                    <td><b>Email</b></td>
                    <td class='text-center'><b>Coupons Distributed</b></td>
                    <td class='text-center'><b>Coupons Redeem</b></td>
                    <td class='text-center'><b>Recent date of redeem</b></td>
                    <td class='text-center'><b>Coupon code</b></td>
                    <td class='text-center' style='width:150px'><b>Action</b></td>
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
                        <td class='text-center poppins font-weight-bold text-danger'>BOOM".$row['coupon']."</td>
                ";
                if($row['total_redeem'] > $row['user_redeem']){
                    $data.= "<td class='text-center'>
                                <button class='btn solid rounded w-100 btn-danger poppins' id='redeem".$row['id']."' onclick='redeemCoupon(".$row['id'].")'>Redeem Coupon</button>
                            </td>";
                }else{
                    $data.= "<td class='text-center text-muted'>All coupons are redeemed.</td>";
                }
                $data.="    </tr>
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
        $date = date('Y-m-d');
        $sql = "SELECT SUM(`total_redeem`) AS `total`, 
                SUM(`user_redeem`) AS `redeem`,
                SUM(ROUND((LENGTH(`dateOfRedeem`) - LENGTH(REPLACE(`dateOfRedeem`, '$date', ''))) / LENGTH('$date'))) AS `today` 
                FROM `offer_applications` WHERE `offerid` = '$offerid'";
        echo json_encode(mysqli_fetch_assoc(mysqli_query($conn, $sql)));
    }

    // redeem coupon
    if(isset($_POST['redeemid'])){
        $id = $_POST['redeemid'];
        $date = date("Y-m-d");
        $response = array();
        $response['success'] = false;

        $sql = "UPDATE `offer_applications` SET `user_redeem`=`user_redeem` + 1,`dateOfRedeem`= CONCAT(`dateOfRedeem` , '$date' , ',') WHERE `id` = '$id'";
        $result = mysqli_query($conn, $sql);
        if($result){
            $response['success'] = true;
        }

        echo json_encode($response);
    }
?>