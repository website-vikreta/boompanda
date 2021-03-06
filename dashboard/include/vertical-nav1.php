<?php 
    include_once "../php/db.php";

    if(!empty($_SESSION['email']) && !empty($_SESSION['userType'])){
        $email = $_SESSION['email'];
        $userType = $_SESSION['userType'];
    }

    $sql = "SELECT `username`, `name`, `profile`, `userType` FROM `user` WHERE `email` = '$email' AND `userType` = '$userType'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
?>

<div class="vertical-nav">
    <div class="logo">
        <img src="./assets/logo.png" alt="boompanda-logo" class="img-fluid">
    </div>
    <?php if($userType == 'admin'){ ?>
    <ul class="nav-list">
        <a href="./index.html" class="nav-link">
            <li class="nav-tab">
                <div class="tab"><i class="far fa-chart-bar"></i> Dashboard</div>
            </li>
        </a>
        <li class="nav-tab dropdown">
            <label for="toggle-check1" class="m-0 p-0">
                <div class="tab"><i class="far fa-rupee-sign"></i> Tasks
                </div>
            </label>
            <!-- checkbox  to manage toggle -->
            <input type="checkbox" name="" id="toggle-check1" class="toggle-check">
            <i class="far fa-chevron-right toggle"></i>
            <ul class="dropdown-menulist">
                <a href="add-task.html" class="nav-link">
                    <li>Add task</li>
                </a>
                <a href="view-tasks.html" class="nav-link">
                    <li>View tasks</li>
                </a>
                <a href="tasks.html" class="nav-link">
                    <li>Tasks</li>
                </a>
                <a href="pending-approvals.html" class="nav-link">
                    <li>Pending approvals</li>
                </a>
            </ul>
        </li>
        <li class="nav-tab dropdown">
            <label for="toggle-check2" class="m-0 p-0">
                <div class="tab"><i class="far fa-chalkboard"></i> Learn
                </div>
            </label>
            <!-- checkbox  to manage toggle -->
            <input type="checkbox" name="" id="toggle-check2" class="toggle-check">
            <i class="far fa-chevron-right toggle"></i>
            <ul class="dropdown-menulist">
                <li>Add activity</li>
                <li>View activities</li>
            </ul>
        </li>
        <li class="nav-tab dropdown">
            <label for="toggle-check3" class="m-0 p-0">
                <div class="tab"><i class="far fa-tags"></i> Offers
                </div>
            </label>
            <!-- checkbox  to manage toggle -->
            <input type="checkbox" name="" id="toggle-check3" class="toggle-check">
            <i class="far fa-chevron-right toggle"></i>
            <ul class="dropdown-menulist">
                <li>Add offer</li>
                <li>View offers</li>
            </ul>
        </li>
        <a href="./users.html" class="nav-link">
            <li class="nav-tab">
                <div class="tab"><i class="far fa-users"></i> Users</div>
            </li>
        </a>
        <div class="nav-link dropdown">
            <li class="nav-tab">
                <label for="toggle-check5" class="m-0 p-0">
                    <div class="tab"><i class="far fa-user-cog"></i> Admins
                    </div>
                </label>
                <!-- checkbox  to manage toggle -->
                <input type="checkbox" name="" id="toggle-check5" class="toggle-check">
                <i class="far fa-chevron-right toggle"></i>
                <ul class="dropdown-menulist">
                    <a href="add-admin.html" class="nav-link">
                        <li>Add admin</li>
                    </a>
                    <a href="view-admins.html" class="nav-link">
                        <li>View all admin</li>
                    </a>
                </ul>
            </li>
        </div>
        <div class="nav-link dropdown">
            <li class="nav-tab">
                <label for="toggle-check6" class="m-0 p-0">
                    <div class="tab"><i class="far fa-exclamation-circle"></i> Components
                    </div>
                </label>
                <!-- checkbox  to manage toggle -->
                <input type="checkbox" name="" id="toggle-check6" class="toggle-check">
                <i class="far fa-chevron-right toggle"></i>
                <ul class="dropdown-menulist">
                    <a href="forms.html" class="nav-link">
                        <li>Form, button, links</li>
                    </a>
                    <a href="tables.html" class="nav-link">
                        <li>Table</li>
                    </a>
                    <li>Card</li>
                    <li>Modal</li>
                </ul>
            </li>
        </div>
    </ul>

    <?php } else if($userType == 'admin'){ ?>
    <?php } else { ?>
        <ul class="nav-list">
        <a href="./index.html" class="nav-link">
            <li class="nav-tab">
                <div class="tab"><i class="far fa-chart-bar"></i> Dashboard</div>
            </li>
        </a>
        <a href="tasks.html" class="nav-link">
            <li class="nav-tab">
                <div class="tab"><i class="far fa-rupee-sign"></i> Tasks</div>
            </li>
        </a>
        <li class="nav-tab dropdown">
            <label for="toggle-check2" class="m-0 p-0">
                <div class="tab"><i class="far fa-chalkboard"></i> Learn
                </div>
            </label>
            <!-- checkbox  to manage toggle -->
            <input type="checkbox" name="" id="toggle-check2" class="toggle-check">
            <i class="far fa-chevron-right toggle"></i>
            <ul class="dropdown-menulist">
                <li>Add activity</li>
                <li>View activities</li>
            </ul>
        </li>
        <li class="nav-tab dropdown">
            <label for="toggle-check3" class="m-0 p-0">
                <div class="tab"><i class="far fa-tags"></i> Offers
                </div>
            </label>
            <!-- checkbox  to manage toggle -->
            <input type="checkbox" name="" id="toggle-check3" class="toggle-check">
            <i class="far fa-chevron-right toggle"></i>
            <ul class="dropdown-menulist">
                <li>Add offer</li>
                <li>View offers</li>
            </ul>
        </li>
        <a href="./users.html" class="nav-link">
            <li class="nav-tab">
                <div class="tab"><i class="far fa-users"></i> Users</div>
            </li>
        </a>
        <div class="nav-link dropdown">
            <li class="nav-tab">
                <label for="toggle-check5" class="m-0 p-0">
                    <div class="tab"><i class="far fa-user-cog"></i> Admins
                    </div>
                </label>
                <!-- checkbox  to manage toggle -->
                <input type="checkbox" name="" id="toggle-check5" class="toggle-check">
                <i class="far fa-chevron-right toggle"></i>
                <ul class="dropdown-menulist">
                    <a href="add-admin.html" class="nav-link">
                        <li>Add admin</li>
                    </a>
                    <a href="view-admins.html" class="nav-link">
                        <li>View all admin</li>
                    </a>
                </ul>
            </li>
        </div>
        <div class="nav-link dropdown">
            <li class="nav-tab">
                <label for="toggle-check6" class="m-0 p-0">
                    <div class="tab"><i class="far fa-exclamation-circle"></i> Components
                    </div>
                </label>
                <!-- checkbox  to manage toggle -->
                <input type="checkbox" name="" id="toggle-check6" class="toggle-check">
                <i class="far fa-chevron-right toggle"></i>
                <ul class="dropdown-menulist">
                    <a href="forms.html" class="nav-link">
                        <li>Form, button, links</li>
                    </a>
                    <a href="tables.html" class="nav-link">
                        <li>Table</li>
                    </a>
                    <li>Card</li>
                    <li>Modal</li>
                </ul>
            </li>
        </div>
    </ul>
    <?php } ?>
</div>

<!-- ! =============================================================== -->
<!-- ! SCRIPT FOR ACTIVE TABS -->
<!-- ! =============================================================== -->
<script>
    // Add active state to sidbar nav links
    var links = $('.nav-link');
    var sublink = $('.nav-tab>ul>a.nav-link');
    var checklink = $(links).hasClass('dropdown');

    $.each(links, function (key, va) {
        if (va.href == document.URL) {
            if (checklink) {
                // if dropdown is present
                $(this, sublink).addClass('active');
                $(this).parents('.nav-link').addClass('active');
            } else {
                //if dropdown is not present
                $(this).addClass('active');
            }
        }
        // opens dropdown when sub tab is selected
        $('.' + links.attr('class') + ' .toggle-check').prop('checked', true);
    });

    //checkbox unchecked
    $(".toggle-check").change(function () {
        $(".toggle-check").not(this).prop('checked', false);
    });
</script>