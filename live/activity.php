<?php
include_once "../dashboard/php/db.php";
extract($_POST);

$superadmin = $email = "";
if (isset($_SESSION['email']) && isset($_SESSION['userType'])) {
    $email = $_SESSION['email'];
    $superadmin = $_SESSION['userType'];
}

if (isset($_GET['accesskey'])) {
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
    <!-- external bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
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
<style>
    .button {
        border: none;
        border-radius: 4px;
    }
</style>

<body>

    <?php
    $sql = "SELECT * FROM `activities` WHERE `token` = '$accesskey'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // get count for today's applications
        $today = date('Y-m-d');
        $numbers2 = "SELECT COUNT(*) AS `todays_application` FROM activity_applications aa , activities a WHERE aa.activityid = a.id and aa.date = DATE(NOW());";
        // $numbers2 = "SELECT count(*) AS `todays_application` FROM `activity_applications` WHERE `id` = '".$row['id']."'  AND `status` = 'accepted'";
        $numbers_sql2 = mysqli_query($conn, $numbers2);
        $number_row2 = mysqli_fetch_assoc($numbers_sql2);

        // get all the activities
        $activity = "SELECT * FROM `activity_applications` WHERE `activityid` = '" . $row['id'] . "' ORDER BY id DESC";
        $activity_result = mysqli_query($conn, $activity);

    ?>
        <!-- ! Content will go here -->

        <div class="container">
            <div class="image text-center">
                <img src="../assets/logo.png" alt="Boompanda Logo" class='img-fluid logo'>
            </div>
            <hr>
            <div class="row align-items-center px-4">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-12">
                            <img src="../dashboard/<?php echo substr($row['logo'], 3); ?>" class="img-fluid p-2" style="width: 100px;  height:100px; border-radius: 50%; box-shadow: 0 0 10px 0 rgba(0,0,0,0.2); max-width:120px" alt="" id="logo">
                        </div>
                        <div class="col-lg-8 col-md-8 col-12 d-flex flex-column justify-content-center">
                            <div class="title-group">
                                <h4 id="title" class="m-0 p-0"><?php echo $row['title']; ?></h4>
                                <span id="category" class="m-0 p-0 bg-light"><?php echo $row['category']; ?></span> <i class="small">by <b id="organizer"><?php echo $row['organizer']; ?></b></i>
                            </div>

                            <div>
                                <p class="start-date p-0 m-0 mt-1 small">Start Date: <b id="start-date" style="font-family: poppins;"><?php echo $row['startDate']; ?></b></p>
                                <p class="end-date p-0 m-0 small">End Date: <b id="end-date" style="font-family: poppins;"><?php echo $row['endDate']; ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="card-wrapper">
                        <div class="card card-primary">
                            <h2 class="number"><?php echo $number_row2['todays_application']; ?></h2>
                            <p class="para">Today's Application</p>
                        </div>
                        <div class="card card-success">
                            <h2 class="number"><?php echo $row['noOfApplication']; ?></h2>
                            <p class="para">Total Applications</p>
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
                                    <td><b>Name</b></td>
                                    <td><b>Date of Application</b></td>
                                    <td><b>Email</b></td>
                                    <td><b>Mobile</b></td>
                                    <td><b>Members</b></td>
                                    <td><b>City</b></td>
                                    <td><b>Status</b></td>

                                </thead>
                                <tfoot>
                                    <td style='width:10px'><b>Sr No</b></td>
                                    <td><b>Name</b></td>
                                    <td><b>Date of Application</b></td>
                                    <td><b>Email</b></td>
                                    <td><b>Mobile</b></td>
                                    <td><b>Members</b></td>
                                    <td><b>City</b></td>
                                    <td><b>Status</b></td>
                                </tfoot>
                                <tbody>
                        ";

                if (mysqli_num_rows($activity_result) > 0) {
                    $number = 1;
                    while ($activity_row = mysqli_fetch_assoc($activity_result)) {
                        $task_id = $row['id'];
                        $name = $activity_row['name'] == '' ? 'NA' : $activity_row['name'];
                        $date = $activity_row['date'] == '' ? 'NA' : $activity_row['date'];
                        $email = $activity_row['email'] == '' ? 'NA' : $activity_row['email'];
                        $mobile = $activity_row['mobile'] == '' ? 'NA' : $activity_row['mobile'];
                        $members = $activity_row['members'] == '' ? 'NA' : $activity_row['members'];
                        $city = $activity_row['city'] == '' ? 'NA' : $activity_row['city'];
                        $status = $activity_row['status'] == '' ? 'NA' : $activity_row['status'];

                        // $members = "SELECT 'members' AS 'member_details' FROM activity_applications";
                        // $submission_result = mysqli_query($conn, $members);
                        // $results = mysqli_fetch_assoc($submission_result);
                        // echo $results['member_details'];
                        $member_details = (explode("},", $members));
                        $data .= "
                                    <tr>
                                        <td class='text-center'>" . $number . "</td>
                                        <td class='poppins'>" . $name . "</td>
                                        <td>" . $date . "</td>
                                        <td>" . $email . "</td>
                                        <td>" . $mobile . "</td>
                                        <td> 
                                        <div class='dropdown'>
                                        <button class='btn-danger button dropdown-toggle' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                                        Member Details</button>
                                        <ul class='dropdown-menu p-4' aria-labelledby='dropdownMenuButton1'>
                                            <li>" . $member_details[0] . "</li>
                                        </ul>
                                        </div>
                                        </td>
                                        <td>" . $city . "</td>
                                        <td>" . $status . "</td>";
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
        //}else{
        //    echo "<h2>Link is expired or Unavailable at this movement</h2>";
        //}
    } else {
        echo "<h2>Link is expired ***** or Unavailable at this movement</h2>";
    }
    ?>

    <script>
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var myObj = JSON.parse(this.responseText);
                document.getElementById("members").innerHTML = myObj.name;
            }
        };
        xmlhttp.open("GET", "json_demo.txt", true);
        xmlhttp.send();
    </script>


    <!-- jquery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- bootstrap external -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- bootstrap js -->
    <script src="../vendor/bootstrap/bootstrap.min.js"></script>
    <!-- datatables -->
    <script src="../vendor/datatables/datatables.min.js"></script>
    <!-- custom js -->
    <script src="./tracking.js"></script>
    <script>
        $('#myTable').DataTable({
            "pageLength": 50
        });
    </script>
</body>

</html>