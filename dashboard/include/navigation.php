<?php 
    include_once "../php/db.php";

    if(!empty($_SESSION['email']) && !empty($_SESSION['userType'])){
        $email = $_SESSION['email'];
        $userType = $_SESSION['userType'];
    }

    $sql = "SELECT `username`, `name`, `profile` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
?>
<div class="links">
    <div class="wallet">
        <i class="far fa-wallet mr-2"></i>
        <div class="amount">&#8377; 500</div>
    </div>
    <div class="notification">
        <div class="btn"><i class="far fa-bell"></i></div>
        <div class="notification-dropdown">
            <ul>
                <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto, enim.</li>
                <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto, enim.</li>
                <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto, enim.</li>
            </ul>
            <div class="link bg-light text-center text-primary small p-2"><a href="#">View All</a>
            </div>
        </div>
    </div>
    <div class="profile">
        <div class="image">
            <?php
                if($row['profile'] == ""){
                    $profile = "../assets/img/profile.jpg";
                }else{
                    $profile = $row['profile'];
                }
            ?>
            <img src="<?php echo $profile ?>" class="img-fluid" alt="">
            <i class="far fa-chevron-down small"></i>
        </div>
        <div class="profile-dropdown">
            <div class="user mt-2">
                <p class="m-0">Hello, <span id="uid" class="font-weight-bold">
                    <?php
                        if($row['name'] == ""){
                            echo $row['username'];
                        }else{
                            echo $row['name'];
                        }
                    ?>
                </span></p>
            </div>
            <hr class="m-0">
            <ul class="pt-0">
                <a href="#">
                    <li>Edit Profile</li>
                </a>
                <a href="#">
                    <li>Change Password</li>
                </a>
                <hr class="m-0">
                <a href="../login/logout.php" class="text-danger font-weight-bold">
                    <li>Logout</li>
                </a>
            </ul>
        </div>
    </div>
    <div class="toggle d-none">
        <div class="btn" id="toggle"><i class="far fa-bars"></i></div>
    </div>
</div>


<script>
    //vertical nav toggle on responsive design
    $("#toggle").on('click', function () {
        $(".vertical-nav").toggleClass('show');
    })
</script>