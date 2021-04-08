<?php
   include_once "./php/db.php";
   $email = $_SESSION['email'];
   $userType = $_SESSION['userType'];

   // values of tasks
   $sql = "SELECT 
            (SELECT COUNT(*) FROM `submissions` WHERE `email` = '$email' AND `userType` = '$userType') AS `total`,
            (SELECT COUNT(*) FROM `submissions` WHERE `status` = 'accepted' AND `email` = '$email' AND `userType` = '$userType') AS `accepted`,
            (SELECT COUNT(*) FROM `submissions` WHERE `status` = 'rejected' AND `email` = '$email' AND `userType` = '$userType') AS `rejected`
           FROM `submissions`";
   $taskStat = mysqli_fetch_assoc(mysqli_query($conn, $sql));

   // total boomcoins earned
   $sql = "SELECT `total_earning` FROM `wallet` WHERE `email` = '$email' AND `userType` = '$userType'";
   $totalEarning = mysqli_fetch_assoc(mysqli_query($conn, $sql));

   // earning per month array calc
   $earningYear = [0,0,0,0,0,0,0,0,0,0,0,0];
   $mindate = date('Y').'-01-01';
   // make sure transaction table has date in DD-MM-YYYY format
   $sql = "SELECT * FROM `transactions` WHERE `email` = '$email' AND `userType` = '$userType' AND `action` = 'credit' AND `date` >= '$mindate'";
   $result = mysqli_query($conn, $sql);
   while($row = mysqli_fetch_assoc($result)){
      $m = explode('-', $row['date']);
      $amt = explode("-", $row['amount']);
      $earningYear[(int)$m[1]-1] += (int)$amt[0];
   }

   // new activities 2
   $sql = "SELECT `title`, `logo` FROM `activities` WHERE `status` = 'Active' ORDER BY `id` DESC LIMIT 2";
   $newactivity = mysqli_query($conn, $sql);

   // applied activity
   $sql = "SELECT * FROM `activities` INNER JOIN `activity_applications` 
            WHERE `activity_applications`.`activityid` = `activities`.`id` AND `activity_applications`.`email` = '$email' 
            AND `activity_applications`.`userType` = '$userType' ORDER BY `activities`.`startDate` LIMIT 1";
   $appliedactivityN = mysqli_query($conn, $sql);
   $appliedactivity = mysqli_fetch_assoc($appliedactivityN);

   // new offers 2
   $sql = "SELECT `title`, `logo` FROM `offers` WHERE `status` = 'Active' ORDER BY `id` DESC LIMIT 2";
   $newoffer = mysqli_query($conn, $sql);

   // applied offer
   $sql = "SELECT * FROM `offers` INNER JOIN `offer_applications` 
            WHERE `offer_applications`.`offerid` = `offers`.`id` AND `offer_applications`.`email` = '$email' 
            AND `offer_applications`.`userType` = '$userType' AND `offer_applications`.`total_redeem` > `offer_applications`.`user_redeem`
            ORDER BY `offers`.`end_date` LIMIT 1";
   $appliedoffer = mysqli_fetch_assoc(mysqli_query($conn, $sql));

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <!-- check for sessions -->
   <script src="../vendor/jquery/jquery.min.js"></script>
   <!-- <input type="hidden" value="all" id="session_user_check"> -->
   <script src="./js/sessions.js"></script>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard | Boompanda</title>
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
   <!-- slick slider -->
   <link rel="stylesheet" href="../vendor/slickslider/slick.css">
   <link rel="stylesheet" href="../vendor/slickslider/slick-theme.css">
   <!-- custom css -->
   <link rel="stylesheet" href="./css/style.css">
   <link rel="stylesheet" href="./css/responsive.css">
   <!-- important scripts -->
   <!-- includes -->
   <script src="./js/include.js"></script>
</head>

<body>

   <div class="wrapper">
      <!-- ! ======================================================== -->
      <!-- ! VERTICAL NAV -->
      <!-- ! ======================================================== -->
      <div id="vertical-nav"></div>
      <!-- ! ======================================================== -->
      <!-- ! VERTICAL NAV -->
      <!-- ! ======================================================== -->

      <div class="main-section">
         <!-- ! ======================================================== -->
         <!-- ! NAV -->
         <!-- ! ======================================================== -->
         <nav>
            <div class="page-loc">
               <p class="m-0">Dashboard</p>
            </div>
            <div id="navigation-bar"></div>
         </nav>
         <!-- ! ======================================================== -->
         <!-- ! NAV -->
         <!-- ! ======================================================== -->

         <!-- ! ======================================================== -->
         <!-- ! CONTENT -->
         <!-- ! ======================================================== -->
         <div class="content-wrapper">
            <div class="updateProfile">
               <p>Complete your profile to start engaging in all Tasks, Offers and Activities on BoomPanda</p>
               <a href="./edit-profile.html" class="btn solid">Complete Now</a>
               <button class="btn close" id="profileClose">X</button>
            </div>
            <div class="cookies">
               <p>Boompanda is using your cookies for improving your user experience.</p>
               <button class="btn solid" id="closeCookie">Ok</button>
            </div>

            <!-- dashbard content starts -->
            <div class="row">
               <div class="col-lg-8 col-md-8 col-12">
                  <!-- carousal -->
                  <div class="slider-wrapper">
                     <div class="slider" id="dashboard-slider">
                        <div class="slide">
                           <a href="https://www.grabon.in/cricketfantasy/" target="_blank" title="Visit Now">
                              <img src="./media/slider/grabon.jpg" class="img-fluid" alt="">
                           </a>
                        </div>
                        <div class="slide">
                           <a href="./tasks.html">
                              <img src="./media/slider/task cover.jpg" class="img-fluid" alt="">
                           </a>
                        </div>
                        <div class="slide">
                           <a href="./offers.html">
                              <img src="./media/slider/offer cover.jpg" class="img-fluid" alt="">
                           </a>
                        </div>
                        <div class="slide">
                           <a href="./activities.html">
                              <img src="./media/slider/activity cover.jpg" class="img-fluid" alt="">
                           </a>
                        </div>
                     </div>
                     <div class="slider-btn-wrapper">
                        <button class="prev slider-btn"><i class="far fa-chevron-left"></i></button>
                        <button class="next slider-btn"><i class="far fa-chevron-right"></i></button>
                     </div>
                  </div>

                  <!-- task statastics -->
                  <div class="task-statastics statastics mt-4">
                     <h5 class="poppins">Tasks</h5>
                     <div class="wrapper">
                        <div class="block">
                           <span class="number"><?php echo $taskStat['total']; ?></span>
                           <p>Submitted</p>
                        </div>
                        <div class="line"></div>
                        <div class="block">
                           <span class="number"><?php echo $taskStat['accepted']; ?></span>
                           <p>Accepted</p>
                        </div>
                        <div class="line"></div>
                        <div class="block">
                           <span class="number"><?php echo $taskStat['rejected']; ?></span>
                           <p>Rejected</p>
                        </div>
                        <div class="line"></div>
                        <div class="block">
                           <span class="number"><?php echo $totalEarning['total_earning']; ?></span>
                           <p>Total Boomcoins Earned</p>
                        </div>
                     </div>
                  </div>

                  <!-- activities -->
                  <div class="activity-statistics statastics mt-4">
                     <h5 class="poppins">Activities</h5>
                     <div class="wrapper align-items-stretch">
                        <?php if(mysqli_num_rows($newactivity) <= 0){ 
                           echo "<p class='textmuted p-3'>Sorry! No activities are available right now. We will soon be back with interesting activities!</p>";                                                  
                        }else{
                           while($row = mysqli_fetch_assoc($newactivity)){ ?>
                              <a href="./activities.html" class="image" title="Apply for this Activity"> 
                                 <img src="./assets/new-gif.gif" alt="" class="new">
                                 <img src="<?php echo substr($row['logo'], 1); ?>" class="img-fluid" alt="">
                                 <p class="m-0 p-2 small"><?php echo $row['title']; ?></p>
                              </a>
                        <?php   }
                            if(mysqli_num_rows($appliedactivityN) > 0){ ?>
                           <a href="./activities.html" class="image" title="Apply for this Activity">
                              <img src="<?php echo substr($appliedactivity['logo'], 1); ?>" class="img-fluid" alt="">
                              <p class="text-danger pb-0 px-2 pt-2 font-weight-bold">Upcoming in your list</p>
                              <p class="m-0 px-2 pb-2 small"><?php echo $appliedactivity['title']; ?></p>
                           </a>
                        <?php }
                        } ?>
                     </div>
                  </div>

                  <!-- offers -->
                  <!-- <div class="offer-statistics activity-statistics statastics mt-4">
                     <h5 class="poppins">Offers</h5>
                     <div class="wrapper align-items-stretch">
                        <?php while($row = mysqli_fetch_assoc($newoffer)){ ?>
                           <a href="./offers.html" class="image" title="Apply for this Activity">
                              <img src="./assets/new-gif.gif" alt="" class="new">
                              <img src="<?php echo substr($row['logo'], 1); ?>" class="img-fluid" alt="">
                              <p class="m-0 p-2 small"><?php echo $row['title']; ?></p>
                           </a>
                        <?php } ?>
                        <a href="./offers.html" class="image" title="Apply for this Activity">
                           <img src="<?php echo substr($appliedoffer['logo'], 1); ?>" class="img-fluid" alt="">
                           <p class="text-danger pb-0 px-2 pt-2 font-weight-bold">Offer expiring soon</p>
                           <p class="m-0 px-2 pb-2 small"><?php echo $appliedoffer['title']; ?></p>
                        </a>
                     </div>
                  </div> -->

               </div>
               <div class="col-lg-4 col-md-4 col-12 statastics">
                  <div class="wrapper justify-content-center">
                     <canvas id="myBarChart" width="100%" height="80"></canvas>
                     <h6 class="poppins mt-3 text-center">Earning per month in <?php echo date('Y'); ?></h6>
                  </div>
               </div>
            </div>
         </div>
         <!-- ! ======================================================== -->
         <!-- ! CONTENT -->
         <!-- ! ======================================================== -->

         <!-- ! ======================================================== -->
         <!-- ! FOOTER -->
         <!-- ! ======================================================== -->
         <div id="footer"></div>
         <!-- ! ======================================================== -->
         <!-- ! FOOTER -->
         <!-- ! ======================================================== -->

      </div>
   </div>


   <!-- jquery -->
   <script src="../vendor/jquery/jquery.min.js"></script>
   <!-- bootstrap js -->
   <script src="../vendor/bootstrap/bootstrap.min.js"></script>
   <!-- slick slider -->
   <script src="../vendor/slickslider/slick.min.js"></script>
   <!-- chart js -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
   <!-- custom js -->
   <script src="./js/main.js"></script>
   <script src="./js/dashboard.js"></script>
   <script>       
      // monthwise bar graph
      // Set new default font family and font color to mimic Bootstrap's default styling
      Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
      Chart.defaults.global.defaultFontColor = '#292b2c';

      // Bar Chart Example
      var ctx = document.getElementById("myBarChart");
      var monthArray = <?php echo '["' . implode('", "', $earningYear) . '"]' ?>;
      var myLineChart = new Chart(ctx, {
         type: 'line',
         data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
               label: "Boomcoins",
               backgroundColor: "#fff",
               borderColor: "#ea1821",
               data: monthArray,
            }],
         },
         options: {
            scales: {
               xAxes: [{
                  time: {
                     unit: 'month'
                  },
                  gridLines: {
                     display: false
                  },
                  ticks: {
                     maxTicksLimit: 6
                  }
               }],
               yAxes: [{
                  ticks: {
                     min: 0,
                     maxTicksLimit: 5
                  },
                  gridLines: {
                     display: true
                  }
               }],
            },
            legend: {
               display: false
            }
         }
      });
   </script>
</body>

</html>