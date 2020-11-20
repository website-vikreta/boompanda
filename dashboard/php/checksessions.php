<?php
    session_start();
    if(isset($_SESSION['email']) and isset($_SESSION['userType'])){
        echo "true";
    }else{
        echo "false";
    }