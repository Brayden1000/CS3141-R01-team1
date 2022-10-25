<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

// Make sure user info is present, if not send them back to home screen
if (!(
    isset($_POST["id"]) &&
    isset($_POST["name"]) &&
    isset($_POST["email"]))) {
                
    $url = "https://mtuelevatordown.000webhostapp.com/";
    header("Location: $url");
    die();
        
}

// Set session values
$_SESSION["id"] = $_POST["id"];
$_SESSION["name"] = $_POST["name"];
$_SESSION["email"] = $_POST["email"];

$_SESSION["logged_in"] = 1;
$url = "https://mtuelevatordown.000webhostapp.com";
header("Location: $url");
die();

?>

<html>
    <head>
        <title>Login</title>
        
        <?php
        
        ?>
        
    </head>

    <body>
        
        <p>
            Please wait. Logging you in now...
        </p>
        
    </body>
</html>
