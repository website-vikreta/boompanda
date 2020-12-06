<?php
    session_start();
    if(isset($_SESSION['userType']) && ($_SESSION['userType'] == 'google' || $_SESSION['userType'] == 'facebook')){
        echo "true";
    }else{
        echo "false";
    }