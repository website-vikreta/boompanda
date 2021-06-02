<?php

use function GuzzleHttp\json_decode;

include_once "./db.php";

// getting session variables
$email = $_SESSION['email'];
$userType = $_SESSION['userType'];


// fetching contact details
if (isset($_GET['contact'])) {
   $sql = "SELECT `user`.`name`, `user`.`email`, `user_info`.`mobile_number`
                FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` 
                WHERE `user`.`email` = '$email' AND `user`.`userType` = '$userType' AND `user_info`.`userType` = '$userType'";
   $result = mysqli_query($conn, $sql);
   $row = mysqli_fetch_assoc($result);
   $row['success'] = true;
   echo json_encode($row);
}

// creating contact
if (isset($_POST['create_contact'])) {
   $response = array();
   $response['success'] = false;
   $name = $mobile = "";
   $flag = 0;

   // name validation
   if (empty($_POST['name'])) {
      $response['nameErr'] = 'Required!';
      $flag = 1;
   } else {
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      if (preg_match("/[^A-Za-z '-]/", $name)) {
         $response['nameErr'] = "Invalid name entered";
         $flag = 1;
      }
   }
   // mobile validation
   if (empty($_POST['mobile'])) {
      $response['mobileErr'] = 'Required!';
      $flag = 1;
   } else {
      $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
      if (!preg_match('/^[0-9]{10}+$/', $mobile)) {
         $response['mobileErr'] = "Invalid mobile number";
         $flag = 1;
      }
   }

   if ($flag == 0) {
      $sql = "SELECT * FROM `ra_contacts` WHERE `email` = '$email' AND `userType` = '$userType'";
      $res = mysqli_query($conn, $sql);
      if (mysqli_num_rows($res) > 0) {
         $response['mobileErr'] = "Contact already created for this account.";
         $flag = 1;
      } else {
         // insert records
         $r = \json_decode(createContact($name, $email, $mobile), true);
         // echo $r;
         if (array_key_exists("id", $r)) {
            $contactID = $r['id'];
            // insert sql
            $sql = "INSERT INTO `ra_contacts`(`email`, `userType`, `contact_id`, `contact_name`, `contact_email`, `contact_mobile`) 
                     VALUES ('$email', '$userType', '$contactID', '$name', '$email', '$mobile')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
               $response['success'] = true;
            }
         } else {
            $response['serverErr'] = "Some error at server side. Try later";
         }
      }
   }

   echo json_encode($response);
}

// adding bebeficiary account
if (isset($_POST['add_beneficiary'])) {
   $response = array();
   $response['success'] = false;
   $name = $mobile = "";
   $flag = 0;

   if ($_POST['accountType'] == 'bank') {
      $accNumber = $accName = $ifsc = "";
      // name validation
      if (empty($_POST['acc-name'])) {
         $response['nameErr'] = 'Required!';
         $flag = 1;
      } else {
         $accName = mysqli_real_escape_string($conn, $_POST['acc-name']);
         if (preg_match("/[^A-Za-z '-]/", $accName)) {
            $response['nameErr'] = "Invalid name entered";
            $flag = 1;
         }
      }

      // account number
      if (empty($_POST['acc-number'])) {
         $response['accErr'] = 'Required!';
         $flag = 1;
      } else {
         $accNumber = mysqli_real_escape_string($conn, $_POST['acc-number']);
         if (preg_match("/[^A-Za-z0-9 '-]/", $accNumber)) {
            $response['accErr'] = "Invalid account number entered";
            $flag = 1;
         }
      }
      if (empty($_POST['c-acc-number'])) {
         $response['c_accErr'] = 'Required!';
         $flag = 1;
      } else {
         $cAccNumber = mysqli_real_escape_string($conn, $_POST['c-acc-number']);
         if ($cAccNumber != $accNumber) {
            $response['c_accErr'] = "Account number does not match";
            $flag = 1;
         }
      }

      // name validation
      if (empty($_POST['ifsc'])) {
         $response['ifscErr'] = 'Required!';
         $flag = 1;
      } else {
         $ifsc = mysqli_real_escape_string($conn, $_POST['ifsc']);
         if (preg_match("/[^A-Za-z0-9 '-]/", $ifsc)) {
            $response['ifscErr'] = "Invalid IFSC entered";
            $flag = 1;
         }
      }

      if ($flag == 0) {
         $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `contact_id` FROM `ra_contacts` WHERE `email` = '$email' AND `userType` = '$userType'"));
         $contactID = $row['contact_id'];
         $r = \json_decode(createFundBank($accNumber, $ifsc, $accName, $contactID), true);
         if (array_key_exists("id", $r)) {
            $fundID = $r["id"];
            // insert sql
            $sql = "INSERT INTO `ra_fundaccounts`(`email`, `userType`, `fundID`) 
                     VALUES ('$email', '$userType', '$fundID')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
               $response['success'] = true;
            }
         } else {
            $response['serverErr'] = "Some error at server side. Try later";
         }
      }
   } else {
      $vpa = "";
      if (empty($_POST['vpa'])) {
         $response['vpaErr'] = 'Required!';
         $flag = 1;
      } else {
         $vpa = mysqli_real_escape_string($conn, $_POST['vpa']);
         if (!preg_match("/^[\w.-]+@[\w.-]+$/", $vpa)) {
            $response['vpaErr'] = "Invalid UPI address";
            $flag = 1;
         }
      }
      if ($flag == 0) {
         $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `contact_id` FROM `ra_contacts` WHERE `email` = '$email' AND `userType` = '$userType'"));
         $contactID = $row['contact_id'];
         $r = \json_decode(createFundVPA($vpa, $contactID), true);
         if (array_key_exists("id", $r)) {
            $fundID = $r["id"];
            // insert sql
            $sql = "INSERT INTO `ra_fundaccounts`(`email`, `userType`, `fundID`) 
                     VALUES ('$email', '$userType', '$fundID')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
               $response['success'] = true;
            }
         } else {
            $response['serverErr'] = "Some error at server side. Try later";
         }
      }
   }

   echo json_encode($response);
}

// fetching recods into table
if (!empty($_POST['readrecord'])) {

   $data = "
   <div class='table-responsive'>
       <table class='table-responsive-sm table-striped' id='myTable' width='100%'>
           <thead>
               <td><b>Sr No</b></td>
               <td><b>Email</b></td></td>
               <td><b>Fund Account ID</b></td></td>
               <td><b>Type</b></td></td>
               <td><b>Account Number</b></td></td>
               <td><b>IFSC</b></td></td>
               <td><b>VPA</b></td></td>
               <td><b>Status</b></td></td>
               <td><b>Action</b></td>
           </thead>
           <tfoot>
               <td><b>Sr No</b></td>
               <td><b>Email</b></td></td>
               <td><b>Fund Account ID</b></td></td>
               <td><b>Type</b></td></td>
               <td><b>Account Number</b></td></td>
               <td><b>IFSC</b></td></td>
               <td><b>VPA</b></td></td>
               <td><b>Status</b></td></td>
               <td><b>Action</b></td>
           </tfoot>
           <tbody>
   ";
   // sql query with inner join
   $sql = "SELECT * FROM `ra_fundaccounts` WHERE `email` = '$email' AND `userType` = '$userType'";
   $result = mysqli_query($conn, $sql);

   if (mysqli_num_rows($result) > 0) {
      $number = 1;
      while ($row = mysqli_fetch_assoc($result)) {
         $type = $row['vpa'] != "" ? "UPI" : "Bank Account";
         $data .= "
               <tr>
                  <td class='text-center'>" . $number . "</td>
                  <td>" . $row['email'] . "</td>
                  <td>" . $row['fundID'] . "</td>
                  <td>" . $type . "</td>
                  <td class='text-center poppins'>" . $row['accountNumber'] . "</td>
                  <td class='poppins'>" . $row['ifsc'] . "</td>
                  <td class='text-center poppins'>" . $row['vpa'] . "</td>
                  <td class='text-danger font-weight-bold poppins text-capitalize text-center'>" . $row['status'] . "</td>
                  <td class='d-flex'>
                     <button class='btn solid btn-primary w-100 user-" . $row['id'] . "' id='approve" . $row['id'] . "' onclick='MakePrimary(" . $row['id'] . ")' data-toggle='tooltip' title='Set this account primary account'>Make Primary</button>
                  </td>
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

// make primary account
if (isset($_POST['approveid'])) {
   $approveid = $_POST['approveid'];
   mysqli_query($conn, "UPDATE `ra_fundaccounts` SET `status`='' WHERE `email` = '$email' AND `userType` = '$userType'");
   $sql = "UPDATE `ra_fundaccounts` SET `status`='primary' WHERE `id` = '$approveid'";
   $result = mysqli_query($conn, $sql);
   if ($result) {
      echo "success";
   } else {
      echo "error";
   }
}


// ? =================================================
// ! RAZORPAY FUNCTIONS
// ? =================================================
// ! CREATIG CONTACT
function createContact($name, $email, $mobile)
{
   $curl = curl_init();
   // json
   curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.razorpay.com/v1/contacts',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
         "name": "' . $name . '",
         "email": "' . $email . '",
         "contact": ' . $mobile . ',
         "type": "Student",
         "reference_id": "Contact created by student"
      }',
      CURLOPT_HTTPHEADER => array(
         'Content-Type: application/json',
         // 'Authorization: Basic cnpwX3Rlc3RfVHdHeURiZ05zS3BFUzc6UWMxNVlzQzdYWG81ekk1Sm5MNFBIWUNu'
         'Authorization: Basic cnpwX3Rlc3RfMFhjMFlGVDQ1T1Yxc206Z0tSaXZHbkpudEVRODc5S3hTZ2tzbTN1'
      ),
   ));
   $response = curl_exec($curl);
   curl_close($curl);
   return $response;
}

// ! UPDATING CONTACT
function updateContact($name, $email, $mobile, $contactID)
{
   $curl = curl_init();
   curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.razorpay.com/v1/contacts/' . $contactID,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'PATCH',
      CURLOPT_POSTFIELDS => '{
         "name": "' . $name . '",
         "email": "' . $email . '",
         "contact": ' . $mobile . ',
         "type": "Student",
         "reference_id": "Contact updated by student",
      } ',
      CURLOPT_HTTPHEADER => array(
         'Content-Type: application/json',
         // 'Authorization: Basic cnpwX3Rlc3RfVHdHeURiZ05zS3BFUzc6UWMxNVlzQzdYWG81ekk1Sm5MNFBIWUNu'
         'Authorization: Basic cnpwX3Rlc3RfMFhjMFlGVDQ1T1Yxc206Z0tSaXZHbkpudEVRODc5S3hTZ2tzbTN1'
      ),
   ));
   $response = curl_exec($curl);
   curl_close($curl);
   return $response;
}

// ! CREATE FUND ACCOUTN BANK
function createFundBank($accNumber, $ifsc, $accName, $contactID)
{
   $curl = curl_init();
   curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.razorpay.com/v1/fund_accounts',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
      "contact_id": "' . $contactID . '",
      "account_type": "bank_account",
      "bank_account": {
            "name": "' . $accName . '",
            "ifsc": "' . $ifsc . '",
            "account_number": "' . $accNumber . '"
         }
      }',
      CURLOPT_HTTPHEADER => array(
         'Content-Type: application/json',
         // 'Authorization: Basic cnpwX3Rlc3RfVHdHeURiZ05zS3BFUzc6UWMxNVlzQzdYWG81ekk1Sm5MNFBIWUNu'
         'Authorization: Basic cnpwX3Rlc3RfMFhjMFlGVDQ1T1Yxc206Z0tSaXZHbkpudEVRODc5S3hTZ2tzbTN1'
      ),
   ));

   $response = curl_exec($curl);
   curl_close($curl);
   return $response;
}

// ! CREATE FUND ACCOUNT VPA
function createFundVPA($vpa, $contactID)
{
   $curl = curl_init();
   curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.razorpay.com/v1/fund_accounts',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
      "contact_id": "' . $contactID . '",
      "account_type": "vpa",
      "vpa": {
            "address": "' . $vpa . '"
         }
      }',
      CURLOPT_HTTPHEADER => array(
         'Content-Type: application/json',
         // 'Authorization: Basic cnpwX3Rlc3RfVHdHeURiZ05zS3BFUzc6UWMxNVlzQzdYWG81ekk1Sm5MNFBIWUNu'
         'Authorization: Basic cnpwX3Rlc3RfMFhjMFlGVDQ1T1Yxc206Z0tSaXZHbkpudEVRODc5S3hTZ2tzbTN1'
      ),
   ));
   $response = curl_exec($curl);
   curl_close($curl);
   return $response;
}
