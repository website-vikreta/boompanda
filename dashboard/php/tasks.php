<?php

    include_once "./db.php";
    extract($_POST);

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){
        $data = "<div class='flex-wrapper'>";
        // sql query with inner join
        $sql = "SELECT * FROM `tasks` WHERE `status` = 'Active' ORDER BY `id` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $data .= "
                    <div class='card'>
                        <div class='image'>
                            <img src='".substr($row['gigLogo'], 1)."' class='img-fluid'>
                        </div>
                        <h3 class='gig-title'>".$row['title']."</h3>
                        <p class='gig-description'>".substr($row['companyDescription'], 0, 200)."......</p>
                        <hr>
                        <p class='secondary'>Amount earn: <span>".$row['boomcoins']." Boomcoins</span></p>
                        <p class='secondary'>Category: ".$row['category']."</p>
                        <p class='secondary'>Start: ".$row['startDate']."</p>
                        <p class='secondary'>End: ".$row['endDate']."</p>

                        <button class='btn solid' id='view".$row['id']."' onclick='ViewTask(".$row['id'].")' data-toggle='modal' data-target='#view-task-modal'>Apply Now</button>
                    </div>
                ";
                    
            }
        }
        $data .= "</div>";
        // $data .= "</table>";
        echo $data;
    }

    // * ====================================
    // * READ SINGLE RECORD
    // * ====================================
    if(isset($_POST['taskid'])){
        $taskid = $_POST['taskid'];
        $sql = "SELECT * FROM `tasks` WHERE `id` = '$taskid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }
