<?php
    session_start();
    if(session_destroy()) // Destroying All Sessions
    {
        header("Location: paperfly_login.php"); // Redirecting To Home Page
    }
?>