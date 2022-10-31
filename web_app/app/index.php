<?php
require "db.php";
session_start(); //Starts session to store cookies and stuff

// Just some test PHP to make sure that the database and php is working together. (UPDATE: It works)
if ( isset($_POST["test"]) ) {
    echo "\n";
    addUser($_POST["email"]);
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
        <script language="JavaScript" type="text/javascript"
                src="https://kjur.github.io/jsrsasign/jsrsasign-latest-all-min.js">
        </script>
        
        <!--
         - Defines the behavior of the sign-in button, providing the OAuth clientID,
         - telling it to not auto-prompt, and to call the given function with the credential
        -->
        <div id="g_id_onload"
            data-client_id="988976111145-c59o1r1o7cln4v1djb8bkgtcmvp5k66j.apps.googleusercontent.com"
            data-auto_prompt="false" data-callback="handleCredentialResponse">
        </div>
        

        
        <?php
        
            if (isset($_SESSION["invalid"])) {
                
                echo "<p>";
                echo "Your email is not from the domain \"mtu.edu.\"<br>";
                echo "You may view elevator status, but please sign-in with a ";
                echo "valid email to make reports.";
                echo "</p>";
                
            }
        
        ?>
        
        <title>Elevator Down</title>
        
    </head>

    <body>
        <div class="topnav">
            <img src="Gold_text_black_background.png" class="mtuimage" alt="MTU Logo">
            <a class="active" href="#listView">List View</a>
            <a href="#mapView">Map View</a>

            <!--
            - This div is what actual renders the login button, feel free to move
            - it when working on the UI
            -->
            <div class="g_id_signin google_button"
                data-type="standard"
                data-size="large"
                data-theme="outline"
                data-text="sign_in_with"
                data-shape="rectangular"
                data-logo_alignment="left">
            </div>
        </div>

        <h1>        
            
        </h1>
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
        <input type="submit" class = "report_button" name="Report" value="Report Elevator">
    </form>
    <?php
    foreach ($elevatorReports as $report) {
        echo "  <p>Report " . $report['id'] . " (" . $report['reporter'] . "): " . $report['comment']  . "</p>";
    }
    
}
?>
    <br><br>
    
    <!--
     - Form that let's me send the user information in POST
     - I'm sending it to login.php so I can handle all the rest of the login
     - there instead of cluttering this one even more.
     -
     - I'm also putting all this in the bottom on purpose, but if you want to
     - move it feel free
    -->
    <form id="send_payload" action="https://mtuelevatordown.000webhostapp.com/login.php" 
              method="post">
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
            
            var isValid = KJUR.jws.JWS.verifyJWT(credential, "616161", {alg: ['HS256']});
                
            var sJWT = credential;
            var headerObj = KJUR.jws.JWS.readSafeJSONString(b64utoutf8(sJWT.split(".")[0]));
            var payloadObj = KJUR.jws.JWS.readSafeJSONString(b64utoutf8(sJWT.split(".")[1]));
                
            return payloadObj;
            
        }
        
    </script>
    
    </body>
</html>