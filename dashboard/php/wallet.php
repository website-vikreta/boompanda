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
                    <td style='width:30px' class='text-center'><b>Sr No</b></td>
                    <td><b>Transaction ID</b></td>
                    <td class='text-center'><b>Transaction Date</b></td>
                    <td class='text-center'><b>Amount</b></td>
                    <td><b>Description</b></td>
                    <td class='text-center'><b>Action</b></td>
                    <td class='text-center'><b>Status</b></td></td>
                </thead>
                <tfoot>
                    <td style='width:30px' class='text-center'><b>Sr No</b></td>
                    <td><b>Transaction ID</b></td>
                    <td class='text-center'><b>Transaction Date</b></td>
                    <td class='text-center'><b>Amount</b></td>
                    <td><b>Description</b></td>
                    <td class='text-center'><b>Action</b></td>
                    <td class='text-center'><b>Status</b></td></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT * FROM `transactions` WHERE `email` = '$email' AND `userType` = '$userType' ORDER BY `id` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <tr>
                        <td class='text-center'>".$number."</td>
                        <td>".$row['transactionID']."</td>
                        <td class='poppins text-center'>".$row['date']."</td>
                        <td class='text-center poppins'>".$row['amount']."</td>
                        <td>".$row['description']."</td>
                        <td class='text-center'>".$row['action']."</td>
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