<?php
    include_once "./db.php";
    extract($_POST);

    $userType = $_SESSION['userType'];
    $email = $_SESSION['email'];

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){

        $data = "
        <div class='table-responsive-lg'>
            <table class='table table-sm table-striped' id='myTable' width='100%'>
                <thead>
                    <td style='width:30px'><b>Sr No</b></td>
                    <td><b>Transaction ID</b></td>
                    <td><b>Transaction Date</b></td>
                    <td><b>Amount</b></td>
                    <td><b>Withdraw Method</b></td>
                    <td><b>Status</b></td></td>
                </thead>
                <tfoot>
                    <td style='width:10px'><b>Sr No</b></td>
                    <td><b>Transaction ID</b></td>
                    <td><b>Transaction Date</b></td>
                    <td><b>Amount</b></td>
                    <td><b>Withdraw Method</b></td>
                    <td><b>Status</b></td></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT * FROM `transactions` WHERE `email` = '$email' AND `userType` = '$userType'";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td class='text-center'>".$row['transactionID']."</td>
                        <td class='text-center'>".$row['date']."</td>
                        <td class='text-center'>".$row['amount']."</td>
                        <td class='text-center'>".$row['withdrawMethod']."</td>
                        <td class='text-center'>".$row['status']."</td>
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

    if(!empty($_POST['getStat'])){
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