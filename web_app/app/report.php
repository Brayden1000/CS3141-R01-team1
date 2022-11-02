<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

echo "Elevator Id: ". $_POST['report_elevator_id'];

/* debugging stuff*/
// print_r($_SESSION);

// If this page is called for an user report, show this [report session variable set in index.php]
if (isset($_POST['report'])) {
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

<?php 
// If this page is called for an admin report, show this [report session variable set in index.php]
} else if (isset($_POST['verify'])) {
?>

<html>
    <head>
        <title>Report</title>
    </head>

    <body>
        <h1>Admin Report</h1>
        <br>
        <h3>Website in Testing Phase</h3>

        <br><br><br><p>Are you sure you want to report this elevator as down?</p>
    </body>
</html>

<?php 
}
?>