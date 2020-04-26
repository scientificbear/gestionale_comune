<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../general/login.php");
    exit;
}

function check_user($this_role, $allowed_list){
    if(!in_array($this_role, $allowed_list)){
        header("location: ../general/unauthorized.php");
        exit;
    }
}

?>