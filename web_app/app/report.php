<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

echo "Elevator Id: ". $_POST['report_elevator_id'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Report</title>
    </head>

    <body>
        <h1>Elevator Down: Report</h1>
        <br>
        <h3>Website in Testing Phase</h3>

        <br><br><br><p>Report stuff and add comment stuff here</p>
    </body>
</html>