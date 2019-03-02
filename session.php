<?php
    session_start(); // Starting Session
    // Storing Session
    error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));
    $user_check = $_SESSION['login_user'];
    $user_type = $_SESSION['user_type'];
    $user_id_chk = $_SESSION['userId'];
    $user_code = $_SESSION['userCode'];
?>