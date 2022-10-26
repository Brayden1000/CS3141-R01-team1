<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

$HOME_URL = "https://mtuelevatordown.000webhostapp.com/";

// Make sure user info is present, if not send them back to home screen
if (!(
    isset($_POST["id"]) &&
    isset($_POST["name"]) &&
    isset($_POST["email"]))) {
                
    header("Location: $HOME_URL");
    die();
        
}

// Set session values
$_SESSION["id"] = $_POST["id"];
$_SESSION["name"] = $_POST["name"];
$_SESSION["email"] = $_POST["email"];

// Make sure logged in is unset at the beginning of the login process
if (isset($_SESSION["logged_in"])) {
    unset($_SESSION["logged_in"]); 
}

// Check if mtu email
$email = $_SESSION["email"];
if (checkUser($email) == 0) {
    $_SESSION["invalid"] = 1;
    header("Location: $HOME_URL");
    die();
}

unset($_SESSION["invalid"]); // Unset the invalid flag if it's a valid email

// Check if in database, if not, add them into it (if this line has been reached
// then it is definitely a valid mtu email)
$result = isUserInDB($email);

if ($result == 0) {
    addUser($email);
}

$_SESSION["is_admin"] = isUserAdmin($email);
$_SESSION["logged_in"] = 1;
header("Location: $HOME_URL");
die();

?>