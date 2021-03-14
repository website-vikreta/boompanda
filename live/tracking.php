<?php
    include_once "../dashboard/php/db.php";
    extract($_POST);

    $superadmin = $email = "";
    if(isset($_SESSION['email']) && isset($_SESSION['userType'])){
        $email = $_SESSION['email'];
        $superadmin = $_SESSION['userType'];
    }    

    if(isset($_GET['accesskey'])){
        $accesskey = $_GET['accesskey'];
    }
    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Tracking | Boompanda</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon-16x16.png">
    <link rel="shortcut icon" href="../assets/favicon.ico">
    <link rel="manifest" href="../assets/site.webmanifest">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="../vendor/bootstrap/bootstrap.min.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="../vendor/fontawesome/all.css">
    <link rel="stylesheet" href="../vendor/fontawesome/all.min.css">
    <!-- data tables -->
    <link rel="stylesheet" href="../vendor/datatables/datatables.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../dashboard/css/style.css">
    <link rel="stylesheet" href="../dashboard/css/responsive.css">
    <link rel="stylesheet" href="./live.css">
    <!-- important scripts -->
</head>

<body>

    <?php
        $sql = "SELECT * FROM `tasks` WHERE `token` = '$accesskey'";
        $result = mysqli_query($conn, $sql);

        if($result && mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            if($row['tracking'] == 'Live' || $superadmin == 'superadmin'){

                // get count for total submissions
                $numbers = "SELECT count(*) AS `total_submission` FROM `submissions` WHERE `taskid` = '".$row['id']."' AND `status` = 'accepted'";
                $numbers_sql = mysqli_query($conn, $numbers);
                $number_row = mysqli_fetch_assoc($numbers_sql);

                // get count for today's submission
                $today = date('Y-m-d');
                $numbers1 = "SELECT count(*) AS `today_submission` FROM `submissions` WHERE `taskid` = '".$row['id']."' AND `dateOfSubmission` = '$today' AND `status` = 'accepted'";
                $numbers_sql1 = mysqli_query($conn, $numbers1);
                $number_row1 = mysqli_fetch_assoc($numbers_sql1);

                // get all the submissions
                $submission = "SELECT * FROM `submissions` WHERE `taskid` = '".$row['id']."' AND `status` = 'accepted' ORDER BY `dateOfSubmission` DESC";
                $submission_result = mysqli_query($conn, $submission);
    ?>            
        <!-- ! Content will go here -->

            <div class="container">
                <div class="row align-items-center px-4">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-12">
                                <img src="../dashboard/<?php echo substr($row['gigLogo'], 3); ?>" class="img-fluid p-2" style="width: 100px;  height:100px; border-radius: 50%; box-shadow: 0 0 10px 0 rgba(0,0,0,0.2); max-width:120px" alt="" id="logo">
                            </div>
                            <div class="col-lg-8 col-md-8 col-12 d-flex flex-column justify-content-center">
                                <div class="title-group">
                                    <h4 id="title" class="m-0 p-0"><?php echo $row['title']; ?></h4>
                                    <span id="category" class="m-0 p-0 bg-light"><?php echo $row['category']; ?></span> <i class="small">by <b id="organizer"><?php echo $row['companyName']; ?></b></i>
                                </div>
                                
                                <div>
                                    <p class="start-date p-0 m-0 mt-1 small">Start Date: <b id="start-date"
                                            style="font-family: poppins;"><?php echo $row['startDate']; ?></b></p>
                                    <p class="end-date p-0 m-0 small">End Date: <b id="end-date"
                                            style="font-family: poppins;"><?php echo $row['endDate']; ?></b></p>
                                </div>  
                            </div>
                        </div>    
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="card-wrapper">
                            <div class="card card-primary">
                                <h2 class="number"><?php echo $number_row1['today_submission']; ?></h2>
                                <p class="para">Today's Submissions</p>
                            </div>
                            <div class="card card-success">
                                <h2 class="number"><?php echo $number_row['total_submission']; ?></h2>
                                <p class="para">Total Submissions</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="content-wrapper">
                    <?php
                        $data = "
                        <div class='table-responsive-md'>
                            <table class='table table-sm table-striped' id='myTable' width='100%'>
                                <thead>
                                    <td style='width:10px'><b>Sr No</b></td>
                                    <td><b>Date</b></td>
                                    <td><b>Name</b></td>
                                    <td><b>City</b></td>
                                    <td><b>Details</b></td>
                                    <td class='text-center'><b>Proof</b><br><span class='font-italic small font-weight-normal'>(Firefox recommended)</span></td>
                                </thead>
                                <tfoot>
                                    <td style='width:10px'><b>Sr No</b></td>
                                    <td style='width:10px'><b>Date</b></td>
                                    <td><b>Name</b></td>
                                    <td><b>City</b></td>
                                    <td><b>Details</b></td>
                                    <td><b>Proof</b></td>
                                </tfoot>
                                <tbody>
                        ";
                    
                        if(mysqli_num_rows($submission_result) > 0){
                            $number = 1;
                            while($submission_row = mysqli_fetch_assoc($submission_result)){
                                $task_id = $row['id'];
                                $data .= "
                                    <tr>
                                        <td class='text-center'>".$number."</td>
                                        <td class='poppins'>".$submission_row['dateOfSubmission']."</td>
                                        <td>".$submission_row['name']."</td>
                                        <td>".$submission_row['city']."</td>
                                        <td>".$submission_row['details']."</td>
                                        <td class='text-center'><a class='text-primary poppins' href='".$submission_row['proofs']."' target='_BLANK'>Click Here</a></td>";
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
                    ?>
                </div>
            </div>


        <!-- ! Content will go here -->


    <?php
            }else{
                echo "<h2>Link is expired or Unavailable at this movement</h2>";
            }
        }else{
            echo "<h2>Link is expired or Unavailable at this movement</h2>";
        }
    ?>


    <!-- jquery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- bootstrap js -->
    <script src="../vendor/bootstrap/bootstrap.min.js"></script>
    <!-- datatables -->
    <script src="../vendor/datatables/datatables.min.js"></script>
    <!-- custom js -->
    <script src="./tracking.js"></script>
    <script>
        $('#myTable').DataTable({"pageLength": 50});
    </script>
</body>

</html>