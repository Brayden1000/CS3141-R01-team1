<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

// Just some test PHP to make sure that the database and php is working together. (UPDATE: It works)
if ( isset($_POST["test"]) ) {
    echo "\n";
    addUser($_POST["email"]);
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
    

<?php

// Request to get Elevator Reports
$elevatorReportCounts = getReportNumber();
foreach ($elevatorReportCounts as $elevator) {
    echo '<h3>Elevator ' . $elevator['id'] . ' at ' . $elevator['location']  . ' has ' . $elevator['downReports'] . ' reports.</h3>';
    $elevatorReports = getReports($elevator['id']);
    ?>
    <form action="report.php" method="post">
        <input type="hidden" name="report_elevator_id" value="<?php echo $elevator['id']; ?>">
        <input type="submit" name="Report" value="Report Elevator">
    </form>
    <?php
    foreach ($elevatorReports as $report) {
        echo "  <p>Report " . $report['id'] . " (" . $report['reporter'] . "): " . $report['comment']  . "</p>";
    }
    
}
?>
    <br><br>
    <a href="./login.php">Login</a>
    </body>
</html>