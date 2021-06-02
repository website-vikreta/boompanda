<?php

include_once "./db.php";
require('razorpay/config.php');
require('razorpay/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

extract($_POST);

// getting session variables
$email = $_SESSION['email'];
$userType = $_SESSION['userType'];

if ($_GET['id']) {
    $id = $_GET['id'];
}

if ($email && $userType && $id && ($userType == 'boompanda' || $userType == 'google' || $userType == 'facebook')) {

    // check for existing record
    $check = "SELECT * FROM `offer_applications` WHERE `email` = '$email' AND `userType` = '$userType' AND `offerid` = '$id'";
    $checkres = mysqli_query($conn, $check);
    if ($checkres) {
        $checkrow = mysqli_fetch_assoc($checkres);
    }

    // fetching user info
    $sql = "SELECT `user`.*, `user_info`.*
            FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` 
            WHERE `user`.`email` = '$email' AND `user`.`userType` = '$userType' AND `user_info`.`userType` = '$userType'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // return on empty profile
    if ($row['name'] == "" || $row['mobile_number'] == "" || $row['current_address'] == "" || $row['college_name'] == "" || $row['dob'] == "") {
        header('location: ../offers.html?flag=profile');
    }

    // fetching offer info
    $sql = "SELECT * FROM `offers` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);
    $row1 = mysqli_fetch_assoc($result);

    $date = date('Y-m-d');
    if (date('Y-m-d', strtotime($row1['end_date'])) < $date) {
        header('location: ../offers.html?flag=ended');
    } else {

        // check for offer is free or not
        if ($row1['offer_type'] == 'free') {
            if ((mysqli_num_rows($checkres) > 0) && ($checkrow['total_redeem'] < $row1['redeem_count'])) {
                $sql = "UPDATE `offer_applications` SET `total_redeem` = `total_redeem` + 1 WHERE `email` = '$email' AND `userType` = '$userType' AND `id` = '" . $checkrow['id'] . "'";
                $inserres = mysqli_query($conn, $sql);
                if ($inserres) {
                    header('location: ../offers.html?flag=success');
                }
            } else if ((mysqli_num_rows($checkres) > 0) && ($checkrow['total_redeem'] >= $row1['redeem_count'])) {
                header('location: ../offers.html?flag=overflow');
                return;
            } else {
                // insert
                $sql = "INSERT INTO `offer_applications`(`name`, `email`, `userType`, `offerid`, `total_redeem`, `dateOfApplication`) 
                    VALUES ('" . $row['name'] . "', '$email', '$userType', '$id', 1 , '$date')";
                // update count
                mysqli_query($conn, "UPDATE `offers` SET `noOfApplication` = `noOfApplication` + 1 WHERE `id` = '" . $row1['id'] . "'");
                $inserres = mysqli_query($conn, $sql);
                if ($inserres) {
                    header('location: ../offers.html?flag=success');
                }
            }
        } else if ($row1['offer_type'] == 'paid') {

            if ((mysqli_num_rows($checkres) > 0) && ($checkrow['total_redeem'] >= $row1['redeem_count'])) {
                header('location: ../offers.html?flag=overflow');
                return;
            } else {
                // ! ==========================================
                // ! RAZORPAY PAYMENT
                // ! ==========================================
                $api = new Api($keyId, $keySecret);
                $orderData = [
                    'receipt'         => 3456,
                    'amount'          => $row1['amount_paid'] * 100, // 2000 rupees in paise
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

                $checkout = 'automatic';

                if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
                    $checkout = $_GET['checkout'];
                }

                $data = [
                    "key"               => $keyId,
                    "amount"            => $amount,
                    "name"              => "Boompanda",
                    "description"       => "Payment for offer - " . $row1['title'],
                    "image"             => "https://www.boompanda.in/assets/android-chrome-512x512.png",
                    "prefill"           => [
                        "name"              => $row['name'],
                        "email"             => $email,
                        "contact"           => $row['mobile_number'],
                    ],
                    "notes"             => [
                        "address"           => $row['current_address'],
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
                $_SESSION['amount'] = $row1['amount_paid'];
                $_SESSION['offerid'] = $row1['id'];
                $_SESSION['redeem'] = $row1['redeem_count'];
                $_SESSION['description'] = "Payment for offer - " . $row1['title'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['checkres'] = mysqli_num_rows($checkres) > 0 ? true : false;
                if ($_SESSION['checkres'] == true) {
                    $_SESSION['total_redeem'] = $checkrow['total_redeem'];
                    $_SESSION['checkrowid'] = $checkrow['id'];
                }

                require("razorpay/checkout/{$checkout}.php");
                // ! ==========================================
                // ! RAZORPAY PAYMENT
                // ! ==========================================
            }
        } else {
            echo "Something went wrong, try again later";
        }
    }
} else {
    header('location: ../offers.html');
}
