<?php

include_once "./db.php";

// getting session variables
$email = $_SESSION['email'];
$userType = $_SESSION['userType'];

extract($_POST);


$sql = "SELECT * FROM `wallet`";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
   $email = $row['email'];
   $userType = $row['userType'];
   $amount = $row['balance'];

   if ($amount > 0) {
      $date = date('d-m-Y');
      $amount = $amount . " Boomcoins";
      $sql = "INSERT INTO `transactions`(`email`, `userType`, `description`, `date`, `amount`, `withdrawMethod`, `action`, `status`) 
              VALUES ('$email', '$userType', 'Credit for - KrazyFox Feedback', '$date', '$amount', 'UPI', 'withdraw', 'success')";
      mysqli_query($conn, $sql);

      $sql = "UPDATE `wallet` SET `balance`= 0 WHERE `email` = '$email' AND `userType` = '$userType'";
      mysqli_query($conn, $sql);
      echo $email . "<br>";
   }
}
