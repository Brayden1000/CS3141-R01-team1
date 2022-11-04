<?php
require "db.php";
session_start();

//if (!isset($_POST["data"])) {
//    header("LOCATION: https://mtuelevatordown.000webhostapp.com");
//    die();
//}

if (isset($_REQUEST['fisher'])) {
    echo "0";
}

// Login
if (isset($_REQUEST['login'])) {
    
    $email = $_REQUEST['login'];
    
    // Handle login stuff
    $isValid = checkUser($email);
    
    if (1 == $isValid) {
        $result = isUserInDB($email);

        if ($result == 0) {
            addUser($email);
        }
        echo "1";
    } else {
        echo "0";
    }
    
}

// Get Elevator Info
if (isset($_REQUEST['info'])) {
    
    $id = $_REQUEST['info'];
    if (0 == strcmp($id, "all")) {
        
        $info = getReportNumber();
        
        for ($i = 0; $i < count($info); $i++) {
            
            echo "".$info[$i]['id']." ";
            echo "".$info[$i]['downReports']." ";
            echo "".$info[$i]['location']." ";
            echo "\n";
            
        }
        
    } else {
        echo "Singular Elevator info not implemented yet. Sorry for the inconvienience.";
    }
    
}

// Is User Admin
if (isset($_REQUEST['admin'])) {
    
    $email = $_REQUEST['admin'];
    
    $isAdmin = isUserAdmin($email);
    
    echo $isAdmin;
    
}

// Make User Report
if (isset($_REQUEST['report'])) {
    
    if (isset($_REQUEST['email']) && isset($_REQUEST['elevatorID'])) {
        
        $email = $_REQUEST['email'];
        $elevID = $_REQUEST['elevatorID'];
        $comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : "";
        
        makeUserReport($email, $elevID, $comment);
        
        echo "1";
        
    } else {
        echo "0";
        echo "Missing email or elevatorID";
    }
    
}

// Verify Elevator Down
if (isset($_REQUEST['verify'])) {
    
    if (isset($_REQUEST['elevatorID']) && isset($_REQUEST['email'])) {
        
        $elevID = $_REQUEST['elevatorID'];
        $email = $_REQUEST['email'];
        
        if (1 == isUserAdmin($email)) {
            verifyElevator($elevID, $email);
            
            echo "1";
        } else {
            echo "-1";
        }
        
    } else {
        echo "0";
    }
    
}

// Get Reports
if (isset($_REQUEST['viewReports'])) {
    
    $elevID = $_REQUEST['viewReports'];
    
    $reports = getReports($elevID);
    $numReports = count($reports);
    echo $numReports."\n";
    
    for ($i = 0; $i < $numReports; $i++) {
        
        echo "".$reports[$i]['elevatorId']."\n";
        echo "".$reports[$i]['id']."\n";
        echo "".$reports[$i]['reporter']."\n";
        echo "\"".$reports[$i]['comment']."\"\n";
        
    }
    
}
    
?>