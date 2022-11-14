<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

// This forces users to connect through the https protocol so that the google
// sign in button works.
if (!isset($_SERVER['HTTPS'])) {
    header ("Location: https://mtuelevatordown.000webhostapp.com");
    die();
}


if (isset($_SESSION['email'])) {
    $_SESSION['is_admin'] = isUserAdmin($_SESSION['email']);
} else {
    $_SESSION['is_admin'] = 0;
}

// Set login session vars to zero by default and if logging out
if (!isset($_SESSION['logged_in']) || isset($_POST['log_out'])) {
    $_SESSION['logged_in'] = 0;
    $_SESSION['id'] = 0;
    $_SESSION['name'] = 0;
    $_SESSION['email'] = 0;
}


/* debugging stuff*/
// print_r($_SESSION);


?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <!--
         - Some scripts that let this actually work, the first is Google's API
         - and the second is what let's me validate the credentials on our end
         - instead of trying to find a way to get Google's validation API to work
        -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script language="JavaScript" type="text/javascript" src="https://kjur.github.io/jsrsasign/jsrsasign-latest-all-min.js">
    </script>

    <!--
         - Defines the behavior of the sign-in button, providing the OAuth clientID,
         - telling it to not auto-prompt, and to call the given function with the credential
        -->
    <div id="g_id_onload" data-client_id="988976111145-c59o1r1o7cln4v1djb8bkgtcmvp5k66j.apps.googleusercontent.com" data-auto_prompt="false" data-callback="handleCredentialResponse">
    </div>



    <title>Elevator Down</title>

</head>

<body class="full_page">
    <div class="topnav">
        <img src="Gold_text_black_background.png" class="mtuimage" alt="MTU Logo">
        <a class="nav_button" href="#listView">List View</a>
        <a class="nav_button" href="#mapView">Map View</a>

        <!--
            - This div is what actual renders the login button, feel free to move
            - it when working on the UI
            -->
        <?php
        if (!$_SESSION['logged_in']) {
        ?>
            <div class="g_id_signin google_button" data-type="standard" data-size="large" data-theme="outline" data-text="sign_in_with" data-shape="rectangular" data-logo_alignment="left">
            <?php
        } else {
            ?>
                <div class="logout_position">
                    <form action="index.php" method="post">
                        <input type="submit" name="log_out" value="Log Out" class="logout_button">
                    </form>
                </div>
            <?php
        }
            ?>
            </div>
    </div>

    <h1>

    </h1>
    <br>
    <div class="main_div">
        <h3>Website in Testing Phase</h3>

        <p id='invalid'>
            Your email is not from the doman "mtu.edu"<br>
            You may view elevator status, but please sign-in with a valid email
            to make reports.
        </p>
        <script>
            var invalid = <?php echo isset($_SESSION['invalid']) ? 1 : 0; ?>;
            var element = document.getElementById('invalid');
            if (1 == invalid) {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        </script>

        <?php
        // Request to get Elevator Reports
        $elevatorReportCounts = getReportNumber(); // Calls an array of data about each elevator including Id, isVerified, and reportNum
        foreach ($elevatorReportCounts as $elevator) { // Iterate and display section for each elevator
            // Display proper elevator section header (depends on elevator's verified status)
            if (isElevatorVerified($elevator['id'])) {
                echo '<h2>Elevator ' . $elevator['id'] . ' at ' . $elevator['location']  . ' is currently down.</h2>';
            } else {
                echo '<h2>Elevator ' . $elevator['id'] . ' at ' . $elevator['location']  . ' has ' . $elevator['downReports'] . ' reports.</h2>';
            }
            // The two buttons inside this if block are only shown if the user is logged in and the elevator is currently functional
            if (isElevatorVerified($elevator['id']) == 0 && $_SESSION['logged_in']) {
        ?>
                <form style="display: inline-block" onsubmit='target_popup(this)' action="report.php" method="post">
                    <input style="display: inline-block" type="hidden" name="report_elevator_id" value="<?php echo $elevator['id']; ?>">
                    <input style="display: inline-block" type="hidden" name="report_elevator_location" value="<?php echo $elevator['location']; ?>">
                    <input style="display: inline-block" type="submit" class="form_button" name="report" value="Report Elevator"> <!-- This is the 'Report Elevator' button which is only shown if logged in -->
                </form>
                    <?php
                    if ($_SESSION['is_admin'] && $_SESSION['logged_in']) {
                    ?>
                        <form style="display: inline-block" action="report.php" method="post">
                            <input style="display: inline-block" type="hidden" name="report_elevator_id" value="<?php echo $elevator['id']; ?>">
                            <input style="display: inline-block" type="submit" class="form_button" name="verify" value="Verify Elevator Down"> <!-- This is the 'Verify Elevator' button which is only shown if logged in as admin -->
                        </form>
                    <?php
                    } // This may be the funniest piece of code I've written
                    ?>
            <?php
            // The code in this if block is shown only if the user is logged in as admin and the elevator is currently verified as down
            } else if (isElevatorVerified($elevator['id']) && $_SESSION['logged_in'] && $_SESSION['is_admin']) {
            ?>
                <form action="report.php" method="post">
                    <input type="hidden" name="report_elevator_id" value="<?php echo $elevator['id']; ?>">
                    <input type="hidden" name="report_elevator_location" value="<?php echo $elevator['location']; ?>">
                    <input type="submit" class="form_button" name="unverify" value="Bring Elevator Up"> <!-- This is the 'Bring Elevator Up' button which is only shown if the elevator is down -->
                </form>
            <?php
            }
            $elevatorReports = getReports($elevator['id']); // Call array of elevator's down reports
            // This loop displays all of the comments the current elevator
            $i = 0;
            foreach ($elevatorReports as $report) {
            ?>
                <div class="<?php echo ($i) ? 'downReport2': 'downReport1';  ?>"> <!-- Changes color of comment backgroud on alternating comments -->
                    <!-- Report details are hidden if not an admin -->
                    <?php echo ($_SESSION['is_admin']) ? "<p>Report " . $report['id'] . " (" . $report['reporter'] . "): " . $report['comment']  . "</p>" : " <p>Report: " . $report['comment']  . "</p>"; ?>
                </div> 
            <?php
            $i = ($i) ? 0 : 1;
            }
        }
        ?>
    </div>
    <br><br>
</body>

<!--
     - Form that let's me send the user information in POST
     - I'm sending it to login.php so I can handle all the rest of the login
     - there instead of cluttering this one even more.
     -
     - I'm also putting all this in the bottom on purpose, but if you want to
     - move it feel free
    -->
<form id="send_payload" action="https://mtuelevatordown.000webhostapp.com/login.php" method="post">
    <input type="hidden" id="id" name="id" value="default"></input>
    <input type="hidden" id="name" name="name" value="default"></input>
    <input type="hidden" id="email" name="email" value="default"></input>
</form>

<!--
     - Below is all the javascript that get's the credntial, decrypts it, formats
     - it into the above form, and then submits it.
    -->
<script>
    // Get's the response from Google's sign-in API, calls another function
    // to decode it, then formats the information into the form.
    function handleCredentialResponse(response) {

        const responsePayload = decodeJwtResponse(response.credential);

        document.getElementById("id").setAttribute("value", responsePayload.sub);
        document.getElementById("name").setAttribute("value", responsePayload.name);
        document.getElementById("email").setAttribute("value", responsePayload.email);
        document.getElementById("send_payload").submit();

    }

    // Don't even ask about this one, I have no clue, it was just copy pasted
    // from where Google told me to look to validate manually
    function decodeJwtResponse(credential) {

        var isValid = KJUR.jws.JWS.verifyJWT(credential, "616161", {
            alg: ['HS256']
        });

        var sJWT = credential;
        var headerObj = KJUR.jws.JWS.readSafeJSONString(b64utoutf8(sJWT.split(".")[0]));
        var payloadObj = KJUR.jws.JWS.readSafeJSONString(b64utoutf8(sJWT.split(".")[1]));

        return payloadObj;

    }
    
    function target_popup(form) {
        window.open('https://mtuelevatordown.000webhostapp.com/report.php', 'formpopup', 'width=600,height=600');
        form.target = 'formpopup';
    }
</script>



</html>