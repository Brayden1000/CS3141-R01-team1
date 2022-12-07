<?php
require "db.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start(); //Starts session to store cookies and stuff

// Closes parent window of popup in case of map-view
if (!isset($_POST['report']) && !isset($_POST['process']) && isset($_SESSION['close'])) {
    echo "super cool stuff";
    unset($_SESSION['close']);
    ?> <script>
        window.close();
    </script> <?php 
    die();
}

// Send automated email if threshold reached and make report
if (isset($_POST['process'])) {
    // Make report
    $email = $_SESSION['email'];
    $id = $_POST['report_id'];
    $comment = $_POST['comment'];
    makeUserReport($email, $id, $comment);

     // Send automated email
     $threshold = 2; 
     if (getReportCount($id) == $threshold) {

         $mail = new PHPMailer(true);
         try {
             //Server settings
             $mail->isSMTP();                                            //Send using SMTP
             $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
             $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
             $mail->Username   = getEmail();                            //SMTP username
             $mail->Password   = getPassword();                         //SMTP password
             $mail->SMTPSecure = 'tls';                                  //Enable implicit TLS encryption
             $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
             //Recipients
             $mail->setFrom('mtuelevatordown@gmail.com', 'Elevator Report');
             $mail->addAddress('wralberg@mtu.edu');              
        
             //Content
             $mail->isHTML(true);                                  //Set email format to HTML
             $mail->Subject = "Elevator " . $id . " reported down";
             $mail->Body    = "Elevator " . $id . " has reached " . $threshold . " down reports and requires verification. Replies to this auto generated email will not be received";
             $mail->AltBody = "Elevator " . $id . " has reached " . $threshold . " down reports and requires verification. Replies to this auto generated email will not be received";
        
             $mail->send();
         } catch (Exception $e) {
             echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
         }
     }
?>
    <!-- Close Window -->
    <script>
        window.opener.location.reload();
        window.close();
    </script>
<?php
    die();   
}

// Opens popup window in the case of map-view
if (isset($_GET['report'])) {
    $_SESSION['close'] = 1;
    unset($_GET['report'])
    ?>
    <form id = "RedirectGet" action="/report.php" method="post" target='formpopup'>
    <input type="hidden" name="report_elevator_id" value="<?php echo $_GET['report_elevator_id']; ?>">
    <input type="hidden" name="report_elevator_location" value="<?php echo str_replace("_", " ", $_GET['report_elevator_location']); ?>">
    <input type="hidden" name="report" value="Report Elevator">
    <input type="hidden" name="testing" value="hello">
    </form>

    <script type="text/javascript">
        window.open('https://mtuelevatordown.000webhostapp.com/report.php', 'formpopup', 'width=600,height=600');
        document.getElementById('RedirectGet').submit(); // SUBMIT FORM
    </script>

    <?php
}

// Renders Report Page
if (isset($_POST['report'])) {
    
    $report_elevator_id = $_POST['report_elevator_id'];
    $report_elevator_location = $_POST['report_elevator_location'];

	foreach (getReportNumber() as $elevator) {
		if ($elevator['id'] == $report_elevator_id) {
			$report_num = $elevator['downReports'];
		}
	}
    
?>
<!-- Close Window -->
<script>
    window.opener.location.reload();
</script>
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