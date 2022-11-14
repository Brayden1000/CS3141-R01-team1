<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

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
    
<?php
if (isset($_POST['report'])) {
?>
    <body class="full_page">
        <div class="main_div">
            <h1> <span>
                You are reporting Elevator <?php echo $_POST['report_elevator_id']; ?> -
                <?php echo str_replace("_", " ", $_POST['report_elevator_location']); ?>
            </span> </h1> 
            <br>
            <p>
                (Optional) Please add your specific issue below.
            </p>
            
            <form action="process.php" method="post">
                <input type="hidden" name="report_id" value="<?php echo $_POST['report_elevator_id']; ?>">
                <textarea name="comment" maxlength="255" rows="10" cols="80"></textarea>
                <br>
                <input type="submit" value="Submit" class="form_button">
            </form>
        </div>
    </body>
    

<?php 
} else if (isset($_POST['verify']) || isset($_POST['unverify'])) {
?>

    

<?php 
}
?>

</html>