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
                              <img src="./media/slider/img-2.jpg" class="img-fluid" alt="">
                           </a>
                        </div>
                        <div class="slide">
                           <a href="#">
                              <img src="./assets/banner.jpg" class="img-fluid" alt="">
                           </a>
                        </div>
                        <div class="slide">
                           <a href="#">
                              <img src="./media/slider/img-1.jpeg" class="img-fluid" alt="">
                           </a>
                        </div>
                        <div class="slide">
                           <a href="#">
                              <img src="./media/slider/img-1.jpeg" class="img-fluid" alt="">
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
                           <span class="number">0</span>
                           <p>Submitted</p>
                        </div>
                        <div class="line"></div>
                        <div class="block">
                           <span class="number">0</span>
                           <p>Accepted</p>
                        </div>
                        <div class="line"></div>
                        <div class="block">
                           <span class="number">0</span>
                           <p>Rejected</p>
                        </div>
                        <div class="line"></div>
                        <div class="block">
                           <span class="number">0</span>
                           <p>Total Boomcoins Earned</p>
                        </div>
                     </div>
                  </div>

                  <!-- activities -->
                  <div class="activity-statistics statastics mt-4">
                     <h5 class="poppins">Activities</h5>
                     <div class="row align-items-stretch">
                        <div class="col-lg-8 col-md-8 col-12 my-2">
                           <div class="wrapper">
                              <a href="#" class="image" title="Apply for this Activity">
                                 <img src="./assets/new-gif.gif" alt="" class="new">
                                 <img src="./assets/tp/1569564008phphXAZrh.jpeg" class="img-fluid" alt="">
                                 <p class="m-0 p-2 small">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit ametdolor sit amet.</p>
                              </a>
                              <a href="#" class="image" title="Apply for this Activity">
                                 <img src="./assets/new-gif.gif" alt="" class="new">
                                 <img src="./assets/tp/abhishek 5-3.jpeg" class="img-fluid" alt="">
                                 <p class="m-0 p-2 small">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit ametdolor sit amet.</p>
                              </a>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12 my-2">
                           <div class="wrapper">
                              <a href="#" class="image w-100" title="Apply for this Activity">
                                 <img src="./assets/tp/abhishek 5-3.jpeg" class="img-fluid" alt="">
                                 <p class="m-0 p-2 small">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit ametdolor sit amet.</p>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- offers -->
                  <div class="offer-statistics statastics mt-4">
                     <h5 class="poppins">Offers</h5>
                     <div class="wrapper">

                     </div>
                  </div>

               </div>
               <div class="col-lg-4 col-md-4 col-12 statastics">
                  <div class="wrapper justify-content-center">
                     <canvas id="myBarChart" width="100%" height="80"></canvas>
                     <h6 class="poppins mt-3 text-center">Earning per month in current year</h6>
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
</body>

</html>