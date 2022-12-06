<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

if (isset($_POST['process'])) {
    
        
    $email = $_SESSION['email'];
    $id = $_POST['report_id'];
    $comment = $_POST['comment'];
    
    makeUserReport($email, $id, $comment);
    
?>

    <script>
        window.opener.location.reload();
        window.close();
    </script>

<?php
    
    //die();
    
}

if (isset($_SESSION['map'])) {
    echo "blah";
    unset($_SESSION['map']);
    ?>
    <script>
        window.opener.location.reload();
        window.close();
    </script>
    <?php
    die();
}

if (isset($_GET['report'])) {
    echo "test";
    $_SESSION['map'] = 1;
    ?>
    <form id = "RedirectGet" onsubmit='target_popup(this)' action="/report.php" method="post">
    <input type="hidden" name="report_elevator_id" value="<?php echo $_GET['report_elevator_id']; ?>">
    <input type="hidden" name="report_elevator_location" value="<?php echo str_replace("_", " ", $_GET['report_elevator_location']); ?>">
    <input type="hidden" name="report" value="Report Elevator">
    <input type="hidden" name="testing" value="hello">
    <input type="submit">
    </form>

    <script type="text/javascript">
        function target_popup(form) {
            window.open('https://mtuelevatordown.000webhostapp.com/report.php', 'formpopup', 'width=600,height=600');
            form.target = 'formpopup';
        }

        //document.getElementById('RedirectGet').submit(); // SUBMIT FORM
    </script>

    <?php
}

if (isset($_POST['report'])) {
    
    if (isset($_POST['testing'])) {
        echo $_POST['testing'];
    }
    
    $report_elevator_id = $_POST['report_elevator_id'];
    $report_elevator_location = $_POST['report_elevator_location'];

	foreach (getReportNumber() as $elevator) {
		if ($elevator['id'] == $report_elevator_id) {
			$report_num = $elevator['downReports'];
		}
	}
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
			<h1>Reports: <?php echo $report_num; ?></h1> 
            <br>
            <p>
                (Optional) Please add your specific issue below.
            </p>
            
            <form action="/report.php" method="post">
                <input type="hidden" name="process" value="1">
                <input type="hidden" name="report_id" value="<?php echo $report_elevator_id; ?>">
                
            	<input type="radio" id="stuck" name="comment" value="Elevator stuck at specific floor">
            	<label for="stuck">Elevator stuck at a specific floor</label><br>
            
            	<input type="radio" id="doors" name="comment" value="Elevator doors won't open">
            	<label for="stuck">Elevator doors won't open</label><br>
            
            	<input type="radio" id="call" name="comment" value="Elevator can't be called from certain floors">
            	<label for="stuck">Elevator can't be called from certain floors</label><br>
            	
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