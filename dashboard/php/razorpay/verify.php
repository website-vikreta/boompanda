<?php

include_once "../db.php";
require('config.php');

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true)
{
    // inserting into transaction table
    $tid = $_POST['razorpay_payment_id'];
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];
    $amount = $_SESSION['amount'];
    $description = $_SESSION['description'];
    $date = date('Y-m-d');
    $sql = "INSERT INTO `transactions`(`email`, `userType`, `transactionID`, `description`, `date`, `amount`, `action`) 
            VALUES ('$email', '$userType', '$tid', '$description', '$date', '$amount', 'payment')";
    $r1 = mysqli_query($conn, $sql);
    // inserting into offer applications
    $offerid = $_SESSION['offerid'];
    $redeem = $_SESSION['redeem'];
    $name = $_SESSION['name'];
    $sql = "INSERT INTO `offer_applications`(`name`, `email`, `userType`, `offerid`, `total_redeem`, `dateOfApplication`) 
            VALUES ('$name', '$email', '$userType', '$offerid', '$redeem', '$date')";
    $r2 = mysqli_query($conn, $sql);
    // increment application count
    mysqli_query($conn, "UPDATE `offers` SET `noOfApplication` = `noOfApplication` + 1 WHERE `id` = '$offerid'");

    if($r1 && $r1){
        unset($_SESSION['amount']);
        unset($_SESSION['offerid']);
        unset($_SESSION['description']);
        unset($_SESSION['name']);

        header('location: ../../offers-u.html?flag=success');
    }
}
else
{
    header('location: ../../offers-u.html?flag=error');
}
