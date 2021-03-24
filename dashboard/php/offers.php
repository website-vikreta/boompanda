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
                            <span class='poppins' title='Cashback Receive'><i class='far fa-wallet mr-1'></i> ".$row['cashback']."%</span>
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