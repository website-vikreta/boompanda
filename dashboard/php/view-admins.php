<?php

    include_once "./db.php";
    extract($_POST);

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){

        $data = "
        <div class='table-responsive'>
            <table class='table-responsive-sm table-striped' id='myTable' width='100%'>
                <thead>
                    <td><b>Sr No</b></td>
                    <td><b>Name</b></td>
                    <td><b>Email</b></td></td>
                    <td><b>City</b></td></td>
                    <td><b>Language</b></td></td>
                    <td><b>Status</b></td>
                    <td><b>Action</b></td>
                </thead>
                <tfoot>
                    <td><b>Sr No</b></td>
                    <td><b>Name</b></td>
                    <td><b>Email</b></td></td>
                    <td><b>City</b></td></td>
                    <td><b>Language</b></td></td>
                    <td><b>Status</b></td>
                    <td><b>Action</b></td>
                </tfoot>
                <tbody>
        ";
        // sql query with inner join
        $sql = "SELECT `user`.id, `user`.`name`, `user`.`email`, `user`.`status`, `user_info`.`city`, `user_info`.`language` 
                FROM `user` INNER JOIN `user_info` ON `user`.`email` = `user_info`.`email` 
                WHERE `user`.`userType` = 'admin'";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <tr>
                        <td>".$number."</td>
                        <td>".$row['name']."</td>
                        <td>".$row['email']."</td>
                        <td>".$row['city']."</td>
                        <td>".$row['language']."</td>
                        <td>".$row['status']."</td>
                        <td class='d-flex'>
                ";
                if($row['status'] == "not verified"){
                    $data .= "
                    <button class='btn solid rounded btn-success user-".$row['id']."' id='approve".$row['id']."' onclick='ApproveAdmin(".$row['id'].")' data-toggle='tooltip' title='Verify'><i class='far fa-check'></i></button>
                    ";
                }else if($row['status'] == "verified"){
                    $data .= "
                    <button class='btn solid rounded btn-info user-".$row['id']."' id='disapprove".$row['id']."' onclick='DisapproveAdmin(".$row['id'].")' data-toggle='tooltip' title='Not Verify'><i class='far fa-times'></i></button>
                    ";
                }
                $data .= "                        
                        <button class='btn solid rounded btn-secondary user-".$row['id']."'><i class='far fa-edit'></i></button>
                        <button class='btn solid rounded btn-danger user-".$row['id']."' id='delete".$row['id']."' onclick='DeleteAdmin(".$row['id'].")' data-toggle='tooltip' title='Remove'><i class='far fa-trash'></i></button>
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

    // * ====================================
    // * APPROVE ADMINS
    // * ====================================
    if(isset($_POST['approveid'])){
        $approveid = $_POST['approveid'];
        $sql = "UPDATE `user` SET `status`='verified' WHERE `id`= '$approveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * DISAPPROVE ADMINS
    // * ====================================
    if(isset($_POST['disapproveid'])){
        $disapproveid = $_POST['disapproveid'];
        $sql = "UPDATE `user` SET `status`='not verified' WHERE `id`= '$disapproveid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }

    // * ====================================
    // * DELETE ADMINS
    // * ====================================
    if(isset($_POST['deleteid'])){
        $deleteid = $_POST['deleteid'];
        $sql = "DELETE `user`, `user_info` FROM `user`
                INNER JOIN `user_info`
                ON `user`.`email` = `user_info`.`email` 
                WHERE `user`.`userType` = 'admin' AND `user`.`id` = '$deleteid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }