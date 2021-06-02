<?php

include_once "../db.php";
require('config.php');

require('razorpay-php/Razorpay.php');

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false) {
   $api = new Api($keyId, $keySecret);

   try {
      // Please note that the razorpay order ID must
      // come from a trusted source (session here, but
      // could be database or something else)
      $attributes = array(
         'razorpay_order_id' => $_SESSION['razorpay_order_id'],
         'razorpay_payment_id' => $_POST['razorpay_payment_id'],
         'razorpay_signature' => $_POST['razorpay_signature']
      );

      $api->utility->verifyPaymentSignature($attributes);
   } catch (SignatureVerificationError $e) {
      $success = false;
      $error = 'Razorpay Error : ' . $e->getMessage();
   }
}

if ($success === true) {
   // inserting into transaction table
   $tid = $_POST['razorpay_payment_id'];
   $email = $_SESSION['email'];
   $userType = $_SESSION['userType'];
   $amount = $_SESSION['amount'];
   $description = $_SESSION['description'];
   $date = date('d-m-Y');
   $sql = "INSERT INTO `transactions`(`email`, `userType`, `transactionID`, `description`, `date`, `amount`, `action`, `status`) 
            VALUES ('$email', '$userType', '$tid', '$description', '$date', '$amount - Rupees', 'payment', 'success')";
   $r1 = mysqli_query($conn, $sql);


   $activityid = $_SESSION['activityid'];
   $name = $_SESSION['name'];
   $mobile = $_SESSION['mobile'];
   $state = $_SESSION['state'];
   $city = $_SESSION['city'];
   $college = $_SESSION['college'];
   $members = $_SESSION['members'];
   $approval = $_SESSION['approval'];

   // inserting into offer applications
   $applicationDate = date('Y-m-d');
   $sql = "INSERT INTO `activity_applications`(`activityid`, `name`, `email`, `userType`, `date`, `mobile`, `state`, `city`, `college`, `members`, `status`) 
                        VALUES ('$activityid', '$name', '$email', '$userType', '$applicationDate', '$mobile', '$state','$city', '$college', '$members', '$approval')";
   $result = mysqli_query($conn, $sql);
   $sql1 = "UPDATE `activities` SET `noOfApplication` = `noOfApplication` + 1 WHERE `id` = '$activityid'";
   $result1 = mysqli_query($conn, $sql1);

   if ($result && $result1) {
      unset($_SESSION['amount']);
      unset($_SESSION['description']);
      unset($_SESSION['name']);
      unset($_SESSION['mobile']);
      unset($_SESSION['state']);
      unset($_SESSION['city']);
      unset($_SESSION['college']);
      unset($_SESSION['members']);
      unset($_SESSION['approval']);
      header('location: ../../activities.html?flag=success');
   }
} else {
   header('location: ../../activities.html?flag=error');
}
