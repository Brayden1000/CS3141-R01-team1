<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

$dbh = connectDB(); //Connect to database

// Just some test PHP to make sure that the database and php is working together. (UPDATE: It works)
if ( isset($_POST["test"]) ) {
    echo "In if";
    $statement1 = $dbh->prepare("INSERT INTO UserData (email, isVerified) VALUES (:email, 0)");
    $statement1->bindParam(":email", $_POST["email"]);
    $statement1->execute();

}
?>
    
<!DOCTYPE html>
<html>
    <head>
        <title>Elevator Down</title>
    </head>


    <body>

        <h1>Elevator Down</h1>
        <br>
        <h3>Website in Testing Phase</h3>
    
        <form method="post">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email"><br>
            <br>
            <input type="submit" name="test" value="Login">
        </form>
    
    </body>
</html>