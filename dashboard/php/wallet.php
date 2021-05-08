<?php
include_once "./db.php";
extract($_POST);

$userType = $_SESSION['userType'];
$email = $_SESSION['email'];

// * ====================================
// * READ RECORDS
// * ====================================
if (!empty($_POST['readrecord'])) {

    $data = "
        <div class='table-responsive-lg'>
            <table class='table table-sm table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='width:30px' class='text-center'><b>Sr No</b></td>
                    <td><b>Description</b></td>
                    <td class='text-center'><b>Transaction Date</b></td>
                    <td class='text-center'><b>Amount</b></td>
                    <td class='text-center'><b>Action</b></td>
                    <td><b>Transaction ID</b></td>
                    <td class='text-center'><b>Status</b></td></td>
                </thead>
                <tfoot>
                    <td style='width:30px' class='text-center'><b>Sr No</b></td>
                    <td><b>Description</b></td>
                    <td class='text-center'><b>Transaction Date</b></td>
                    <td class='text-center'><b>Amount</b></td>
                    <td class='text-center'><b>Action</b></td>
                    <td><b>Transaction ID</b></td>
                    <td class='text-center'><b>Status</b></td></td>
                </tfoot>
                <tbody>
        ";
    // sql query with inner join
    $sql = "SELECT * FROM `transactions` WHERE `email` = '$email' AND `userType` = '$userType' ORDER BY `id` DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data .= "
                    <tr>
                        <td class='text-center'>" . $number . "</td>
                        <td>" . $row['description'] . "</td>
                        <td class='poppins text-center'>" . $row['date'] . "</td>
                        <td class='text-center poppins'>" . $row['amount'] . "</td>
                        <td class='text-center'>" . $row['action'] . "</td>
                        <td>" . $row['transactionID'] . "</td>
                        <td class='text-center'>" . $row['status'] . "</td>
                    </tr>";
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

if (!empty($_POST['getStat'])) {
    $response = array();
    $sql = "SELECT * FROM `wallet` WHERE `email` = '$email' AND `userType` = '$userType'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $response['earning'] = $row['total_earning'];
    $response['balance'] = $row['balance'];

    $sql = "SELECT SUM('pending_boomcoins') AS `pending_boomcoins` FROM `applications` WHERE `email` = '$email' AND `userType` = '$userType'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $response['pending'] = $row['pending_boomcoins'];

    echo json_encode($response);
}

// get balance & other details
if (!empty($_GET['fetchAmount'])) {
    $response = array();
    // get balance
    $sql = "SELECT `balance` FROM `wallet` WHERE `email` = '$email' AND `userType` = '$userType' ";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response['wallet'] = $row['balance'];
    }
    // get primary account
    $sql = "SELECT * FROM `ra_fundaccounts` WHERE `email` = '$email' AND `userType` = '$userType' AND `status` = 'primary'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['vpa'] != "") {
            $response['account'] = "Primary UPI is: xxxxxxxx" . substr($row['vpa'], 8);
        } else {
            $response['account'] = "Primary account selected is xxxxxxxx" . substr($row['accountNumber'], -4);
        }
    } else {
        $response['account'] = "<span class='text-danger'>Oops no account / beneficiary is added. Kindly add account/UPI first. If already did check whether is set to primary or not.</span>";
    }
    echo json_encode($response);
}

if (isset($_POST['withdraw'])) {
    $response = array();
    $response['success'] = false;
    $flag = 0;
    $balance = $fundID = $type = $avail = "";
    $response['boomcoinErr'] = "";
    // check for available balance
    $sql = "SELECT `balance` FROM `wallet` WHERE `email` = '$email' AND `userType` = '$userType' ";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['balance'] < $_POST['amount'] || $row['balance'] == 0) {
            $response['boomcoinErr'] .= "Balance is not sufficient! <br>";
            $flag = 1;
        } else {
            $balance = mysqli_real_escape_string($conn, $_POST['amount']);
            $avail = $row['balance'] - $balance;
            if (!preg_match('/^[0-9]+$/', $balance)) {
                $response['boomcoinErr'] = "Invalid amount";
                $flag = 1;
            } else if ($balance < 1000) {
                $response['boomcoinErr'] = "Atleast 1000 boomcoins required to withdraw.";
                $flag = 1;
            }
        }
    }
    // check for fund account availability
    $sql = "SELECT * FROM `ra_fundaccounts` WHERE `email` = '$email' AND `userType` = '$userType' AND `status` = 'primary'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) <= 0) {
        $response['boomcoinErr'] .= "Primary account does not exists. Kindly add bank account/UPI.";
        $flag = 1;
    } else {
        $row = mysqli_fetch_assoc($result);
        $fundID = $row['fundID'];
        $type = $row['vpa'] == "" ? "IMPS" : "UPI";
    }

    if ($flag == 0) {
        // create payout request
        $amount = (($balance / 10) - 2) * 100;
        $payout = \json_decode(createPayout($fundID, $amount, $type), true);
        if (array_key_exists("id", $payout)) {
            $payoutID = $payout["id"];
            $date = date('d-m-Y');
            $amount = $amount / 100;
            // insert sql
            $sql = "INSERT INTO `transactions`(`email`, `userType`, `transactionID`, `description`, `date`, `amount`, `action`, `status`) 
                    VALUES ('$email', '$userType', '$payoutID', 'Payout', '$date', '$amount - Rupees', 'Withdraw', 'success')";
            $result = mysqli_query($conn, $sql);
            // update wallet
            $sql = "UPDATE `wallet` SET `balance`='$avail' WHERE `email` = '$email' AND `userType` = '$userType'";
            $result1 = mysqli_query($conn, $sql);
            if ($result && $result1) {
                $response['success'] = true;
            }
        } else {
            $response['serverErr'] = $payout;
        }
    }
    echo json_encode($response);
}




function createPayout($fundID, $amount, $type)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.razorpay.com/v1/payouts',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "account_number": "2323230029717497",
            "fund_account_id": "' . $fundID . '",
            "amount": ' . $amount . ',
            "currency": "INR",
            "mode": "' . $type . '",
            "purpose": "payout",
            "queue_if_low_balance": true,
            "reference_id": "Payout request generated by Student"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            // 'Authorization: Basic cnpwX3Rlc3RfVHdHeURiZ05zS3BFUzc6UWMxNVlzQzdYWG81ekk1Sm5MNFBIWUNu'
            'Authorization: Basic cnpwX3Rlc3RfMFhjMFlGVDQ1T1Yxc206Z0tSaXZHbkpudEVRODc5S3hTZ2tzbTN1'
        ),
    ));
    // "account_number": "2323230086780610",
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
