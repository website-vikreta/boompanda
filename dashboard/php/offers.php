<?php

    include_once "./db.php";
    extract($_POST);

    // getting session variables
    $email = $_SESSION['email'];
    $userType = $_SESSION['userType'];

    // * ====================================
    // * READ RECORDS
    // * ====================================
    if(!empty($_POST['readrecord'])){
        $data = "<div class='flex-wrapper'>";
        // sql query with inner join
        $sql = "SELECT * FROM `offers` WHERE `status` = 'Active' ORDER BY `id` DESC";
        $result=mysqli_query($conn,$sql);
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row = mysqli_fetch_assoc($result)){
                $amount = $row['offer_type']=='paid'? "₹ " . $row['amount_paid']:'Free';
                $cashback_type = $row['cashback_type'] == "rupees"? "₹": "%";
                $data .= "
                    <div class='card mb-4' style='width:215px !important;'>
                        <div class='amount'>".$amount."</div>
                        <div class='date' id='date_".$row['id']."' title='Days Left'>
                            <span id='days'></span>
                            <span id='hours'></span>
                            <span id='mins'></span>
                            <span id='secs'></span>
                            <span id='end'></span>
                        </div>
                        <script>runTimer(".$row['id'].", '".$row['end_date']."')</script>
                        <div class = 'head pt-4'>
                            <div class='image p-1 mt-3'>
                                <img src='".substr($row['logo'], 1)."' class='img-fluid p-0' style='border-radius: 50px'>
                            </div>
                            <h3 class='gig-title'>".substr($row['title'], 0, 30)."...</h3>
                        </div>
                        <div class='time flex-center justify-content-between'>
                            <span class='poppins' title='Cashback Receive'><i class='far fa-wallet mr-1'></i> ".$row['cashback']." ".$cashback_type."</span>
                            <span title='Times you can redeem'>Redeem <span style='all:initial; font-family: var(--font-poppins);font-size: 0.8rem'> ".$row['redeem_count']." </span> times</span>
                        </div>
                        <a>".$row['category']."</a>

                        <button class='btn solid' id='view".$row['id']."' onclick='viewOffer(".$row['id'].")' data-toggle='modal' data-target='#view-offer-modal'>Avail Offer</button>
                    </div>
                ";
                    
            }
        }else{
            $data .= "<p class='text-muted text-center small p-5 w-100'>No active activities available at this moment, try again after some time.</p>";
        }
        $data .= "</div>";
        // $data .= "</table>";
        echo $data;
    }

    // * ====================================
    // * APPLIED OFFERS
    // * ====================================
    if(!empty($_POST['readapplied'])){
        $data = "<div class='flex-wrapper'>";
        // sql query with inner join
        $sql = "SELECT * FROM `offer_applications` WHERE `email` = '$email' AND `userType` = '$userType' ORDER BY `id` DESC";
        $result = mysqli_query($conn,$sql);

        // get coupon
        $sql = "SELECT `uid` FROM `user_info` WHERE `email` = '$email' AND `userType` = '$userType'";
        $u = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        $coupon = "BOOM".$u['uid'];
    
        if(mysqli_num_rows($result) > 0){
            $number = 1;
            while($row1 = mysqli_fetch_assoc($result)){
                $sql = "SELECT * FROM `offers` WHERE `id` = '".$row1['offerid']."'";
                $res = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($res);
                // finding total redeem
                $sql = "SELECT SUM(`user_redeem`) AS `user_redeem` FROM `offer_applications` WHERE `offerid` = '".$row1['offerid']."' ";
                $t = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                

                if(date('Y-m-d', strtotime($row['end_date'])) >= date('Y-m-d')){
                    $data .= "
                        <div class = 'card coupon'>
                            <div class='heading'><span>E - coupon</span></div>

                            <div class='offer-logo'><img src='".substr($row['logo'], 1)."' class='img-fluid'></div>
                            <h3 class='title'>".$row['title']."</h3>
                            <p class='location bar'><i class='m-1 mr-3 fas fa-map-marker-alt'></i>".substr($row['location'],0, 25)."...</p>
                        ";
                        if($row['college_name'] != ""){
                            $data.=" <p class='address bar'><i class='m-1 mr-2 fas fa-graduation-cap'></i>".$row['college_name']."</p>";
                        }
                        $data.="    <p class='m-0 small'>Rewards:</p>
                            <p class='m-0 rewards poppins'><i class='far fa-wallet'></i> ".$row['cashback']."% Cashback</p>
                            <p class='coupon bar'>
                                <span>".$coupon."</span>
                            </p>
                            <div class='stat'>
                                <div class='info'>
                                    <i class='far fa-clock'></i>
                                    <div class='numbers'>
                                        <p class='m-0 small'>Valid till</p>
                                        <p class='m-0 big'>".$row['end_date']."</p>
                                    </div>
                                </div>
                                <div class='info'>
                                    <i class='far fa-fire'></i>
                                    <div class='numbers'>
                                        <p class='m-0 small'>Redeem by</p>
                                        <p class='m-0 big'>".$t['user_redeem']." Users</p>
                                    </div>
                                </div>
                                <div class='info'>
                                    <i class='far fa-user-tag'></i>
                                    <div class='numbers'>
                                        <p class='m-0 small'>Your redeem</p>
                                        <p class='m-0 big'>".$row1['user_redeem']." / ".$row1['total_redeem']."</p>
                                    </div>
                                </div>
                                <button class='m-0'>
                                    <span>View Details</span>
                                </button>
                            </div>

                        </div>
                    ";
                }else{
                    $data .= "
                        <div class = 'card coupon' style='opacity: 0.2'>
                        <div class='heading'><span>E - coupon</span></div>

                        <div class='offer-logo'><img src='".substr($row['logo'], 1)."' class='img-fluid'></div>
                        <h3 class='title'>".$row['title']."</h3>
                        <p class='location bar'><i class='m-1 mr-3 fas fa-map-marker-alt'></i>".substr($row['location'],0, 25)."...</p>
                    ";
                    if($row['college_name'] != ""){
                        $data.=" <p class='address bar'><i class='m-1 mr-2 fas fa-graduation-cap'></i>".$row['college_name']."</p>";
                    }
                    $data.="    <p class='m-0 small'>Rewards:</p>
                        <p class='m-0 rewards poppins'><i class='far fa-wallet'></i> ".$row['cashback']."% Cashback</p>
                        <p class='coupon bar'>
                            <span>".$coupon."</span>
                        </p>
                        <div class='stat'>
                            <div class='info'>
                                <i class='far fa-clock'></i>
                                <div class='numbers'>
                                    <p class='m-0 small'>Valid till</p>
                                    <p class='m-0 big'>".$row['end_date']."</p>
                                </div>
                            </div>
                            <div class='info'>
                                <i class='far fa-fire'></i>
                                <div class='numbers'>
                                    <p class='m-0 small'>Redeem by</p>
                                    <p class='m-0 big'>".$t['user_redeem']." Users</p>
                                </div>
                            </div>
                            <div class='info'>
                                <i class='far fa-user-tag'></i>
                                <div class='numbers'>
                                    <p class='m-0 small'>Your redeem</p>
                                    <p class='m-0 big'>".$row1['user_redeem']." / ".$row1['total_redeem']."</p>
                                </div>
                            </div>
                            <button class='m-0' disabled>
                                <span>View Details</span>
                            </button>
                        </div>

                    </div>
                    ";
                }
                    
            }
        }else{
            $data .= "<p class='text-muted text-center small p-5 w-100'>You havn't applied to any task / gig yet. Apply to task by click Active task button on top.</p>";
        }
        $data .= "</div>";
        // $data .= "</table>";
        echo $data;
    }