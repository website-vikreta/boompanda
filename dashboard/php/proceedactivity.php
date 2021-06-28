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
// * APPLY
// * ====================================
if (isset($_GET['activityId']) && isset($_GET['approval'])) {
    $id = $_GET['activityId'];
    $teamSize = $_GET['teamSize'];
    $members = $_GET['members'];
    $sql = "SELECT * FROM `activities` WHERE `id` = '$id'";
    $activityresult = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $approval = ($activityresult['approval'] == "Yes") ? "Under Review" : "Active";
    // user details
    $name = $_GET['name'];
    $mobile = $_GET['mobile'];
    $email = $_GET['email'];
    $state = $_GET['state'];
    $city = $_GET['city'];
    $college = $_GET['college'];

    $response = array();
    $response['success'] = false;

    $sql = "SELECT * FROM `activity_applications` WHERE `email` = '$email' AND `activityid` = '$id' AND `userType` = '$userType'";
    if (mysqli_num_rows(mysqli_query($conn, $sql)) > 0) {
        header('location: ../activities.html?flag=alreadyapplied');
        // $response['modalErr'] = "You already applied for this activity";
    } else {

        // for paid activity & free activity
        if ($activityresult['type'] == 'Free') {
            $date = date("Y-m-d");
            $sql = "INSERT INTO `activity_applications`(`activityid`, `name`, `email`, `userType`, `date`, `mobile`, `state`, `city`, `college`, `members`, `status`) 
                        VALUES ('$id', '$name', '$email', '$userType', '$date', '$mobile', '$state','$city', '$college', '$members', '$approval')";
            $result = mysqli_query($conn, $sql);
            $sql1 = "UPDATE `activities` SET `noOfApplication` = `noOfApplication` + 1 WHERE `id` = '$id'";
            $result1 = mysqli_query($conn, $sql1);
            if ($result && $result1) {
                header('location: ../activities.html?flag=success');
            }
        } else if ($activityresult['type'] == 'Paid') {
            // ! ==========================================
            // ! RAZORPAY PAYMENT
            // ! ==========================================
            $api = new Api($keyId, $keySecret);
            $amt = round(($activityresult['amountPaid'] * 100) / 0.98);
            $orderData = [
                'receipt'         => 3456,
                'amount'          => $amt, // 2000 rupees in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];

            $razorpayOrder = $api->order->create($orderData);

            $razorpayOrderId = $razorpayOrder['id'];

            $_SESSION['razorpay_order_id'] = $razorpayOrderId;

            $displayAmount = $amount = $orderData['amount'];

            if ($displayCurrency !== 'INR') {
                $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
                $exchange = json_decode(file_get_contents($url), true);

                $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
            }

            $checkout = 'activity-checkout';

            if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['activity-checkout'], true)) {
                $checkout = $_GET['checkout'];
            }

            $data = [
                "key"               => $keyId,
                "amount"            => $amount,
                "name"              => "Boompanda",
                "description"       => "Payment for Activity - " . $activityresult['title'],
                "image"             => "https://www.boompanda.in/assets/android-chrome-512x512.png",
                "prefill"           => [
                    "name"              => $name,
                    "email"             => $email,
                    "contact"           => $mobile,
                ],
                "notes"             => [
                    "address"           => $city,
                    "merchant_order_id" => "12312321",
                ],
                "theme"             => [
                    "color"             => "#ea1821"
                ],
                "order_id"          => $razorpayOrderId,
            ];

            if ($displayCurrency !== 'INR') {
                $data['display_currency']  = $displayCurrency;
                $data['display_amount']    = $displayAmount;
            }

            $json = json_encode($data);
            $_SESSION['amount'] = $activityresult['amountPaid'];
            $_SESSION['name'] =  $name;
            $_SESSION['mobile'] =  $mobile;
            $_SESSION['state'] =  $state;
            $_SESSION['city'] =  $city;
            $_SESSION['college'] =  $college;
            $_SESSION['approval'] =  $approval;
            $_SESSION['members'] =  $members;
            $_SESSION['activityid'] = $id;
            $_SESSION['description'] = "Payment for Activity - " . $activityresult['title'];

            require("razorpay/checkout/{$checkout}.php");
        }
    }
}
