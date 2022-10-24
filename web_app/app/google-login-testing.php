<html>
    
    <head>
        
        <?php
           require_once 'google-api/vendor/autoload.php';
        ?>
        
      <script src="https://accounts.google.com/gsi/client" async defer></script>
      <div id="g_id_onload"
         data-client_id="988976111145-c59o1r1o7cln4v1djb8bkgtcmvp5k66j.apps.googleusercontent.com"
         data-login_uri="https://mtuelevatordown.000webhostapp.com/google-login-testing.php"
         data-auto_prompt="true">
      </div>
      <div class="g_id_signin"
         data-type="standard"
         data-size="large"
         data-theme="outline"
         data-text="sign_in_with"
         data-shape="rectangular"
         data-logo_alignment="left">
      </div>
        
    </head>
    
    <body>
        
        <h1>TEST</h1>
        
        <?php
        
            // Cannot currently run because the library to work with php is too
            // large and 000webhost won't let me run a python script.
            
            // The below code should work once the library is fully uploaded though.
        
            if (isset($_POST["credential"])) {
                
                echo "test";

                $CLIENT_ID = "988976111145-c59o1r1o7cln4v1djb8bkgtcmvp5k66j.apps.googleusercontent.com";
                $id_token = $_POST["credential"];

                $client = new Google_Client(['client_id' => $CLIENT_ID]);
                $payload = $client->verifyIdToken($id_token);
                if ($payload) {
                    
                    // This is where we get the user information, a list of
                    // all parts of payload can be found here:
                    // https://developers.google.com/identity/gsi/web/reference/html-reference
                    
                    $userid = $payload['sub'];
                    $email = $payload['email'];
                    
                    echo $email;
                    
                } else {
                    // Invalid ID token
                }
                
                
            }
        
        ?>
        
    </body>
    
</html>