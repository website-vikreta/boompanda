<?php

include_once "./db.php";
extract($_POST);

// ! fetching records in wallet table
// $sql = "SELECT * FROM `user`";
// $result = mysqli_query($conn, $sql);
// if(mysqli_num_rows($result) > 0){
//     while($row = mysqli_fetch_assoc($result)){
//         $email = $row['email'];
//         $userType = $row['userType'];
//         mysqli_query($conn, "INSERT INTO `wallet`(`email`, `userType`) VALUES ('$email', '$userType')");
//     }
// }