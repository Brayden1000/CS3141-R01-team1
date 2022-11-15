<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

if (isset($_POST['report']) || isset($_GET['report'])) {
    $report_elevator_id = (isset($_POST['report'])) ? $_POST['report_elevator_id'] : $_GET['report_elevator_id'];
    $report_elevator_location = (isset($_POST['report'])) ? $_POST['report_elevator_location'] : $_GET['report_elevator_location']
?>
<!DOCTYPE html>
<html>
    
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Report</title>
        
        <style>
            textarea {
                resize: none;
            }
        </style>
    </head>
    
    <body class="full_page">
        <div class="main_div">
            <h1> <span>
                You are reporting Elevator <?php echo $report_elevator_id; ?> -
                <?php echo str_replace("_", " ", $report_elevator_location); ?>
            </span> </h1> 
            <br>
            <p>
                (Optional) Please add your specific issue below.
            </p>
            
            <form action="process.php" method="post">
                <input type="hidden" name="report_id" value="<?php echo $report_elevator_id; ?>">
                <textarea name="comment" maxlength="255" rows="10" cols="80"></textarea>
                <br>
                <input type="submit" value="Submit" class="form_button">
            </form>
        </div>
    </body>
    

<?php 
} else if (isset($_POST['verify'])) {
    
    verifyElevator($_POST['report_elevator_id'], $_SESSION['email']);
    header("Location: /index.php");
    die();
    
} else if (isset($_POST['unverify'])) {
    
    unverifyElevator($_POST['report_elevator_id']);
    header("Location: /index.php");
    die();
    
}
?>

</html>