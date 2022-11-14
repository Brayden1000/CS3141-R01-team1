<?php
require "db.php";
session_start();

if (isset($_POST['report_id'])) {
        
    $email = $_SESSION['email'];
    $id = $_POST['report_id'];
    $comment = $_POST['comment'];
    
    makeUserReport($email, $id, $comment);
        
}

header("Location: https://mtuelevatordown.000webhostapp.com");
die();

?>