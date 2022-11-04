<?php
function connectDB() {
    try {
        $config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function isUserInDB($email) {
    $dbh = connectDB();
    
    $statement = $dbh->prepare("SELECT * FROM UserData WHERE email = :email");
    $statement->bindParam(":email", $email);
    $statement->execute();
    
    $i = 0;
    while ($row = $statement->fetch()) {
        $i++;
    }
    
    $dbh = null;
    return $i;
}

function isUserAdmin($email) {
    $dbh = connectDB();
    
    $statement = $dbh->prepare("SELECT isAdmin FROM UserData WHERE email = :email");
    $statement->bindParam(":email", $email);
    $statement->execute();
    
    $isAdmin = $statement->fetch()[0];
    
    $dbh = null;
    return $isAdmin;
}

function addUser($email) {
    $dbh = connectDB();

    if (checkUser($email)) {
        $statement1 = $dbh->prepare("INSERT INTO UserData (email, isAdmin) VALUES (:email, 0)");
        $statement1->bindParam(":email", $email);
        $statement1->execute();
    } else {
        echo "Invalid Email";
    }

    $dbh = null;
}

function checkUser($email) {
    $arr = explode("@", $email);
    if ($arr[1] == "mtu.edu") {
        return 1;
    } else {
        return 0;
    }
}

function verifyUser($email) {
    $dbh = connectDB();
    
    $isAdmin = isUserAdmin($email) == 1 ? 0 : 1;
    
    $statement1 = $dbh->prepare("UPDATE UserData SET isAdmin = :isAdmin WHERE email = :email;");
    
    $statement1->bindParam(":email", $email);
    $statement1->bindParam(":isAdmin", $isAdmin);
    $statement1->execute();

    $dbh = null;
}

function verifyElevator($elevatorId) {
    $dbh = connectDB();

    $statement1 = $dbh->prepare("UPDATE ElevatorInfo
                                 SET isDownVerified = 1, timeSinceVerified = :timeSince, whoVerified = :email
                                WHERE elevatorId = :elevatorId;");
    $statement1->bindParam(":elevatorId", $elevatorId);
    $statement1->bindParam(":email", $email);
    $statement1->bindParam(":timeSince", time());

    $statement1->execute();

    $dbh = null;
}

function makeUserReport($userEmail, $elevatorId, $comment) {
    $dbh = connectDB();

    $statement1 = $dbh->prepare("UPDATE ElevatorInfo SET downReports = downReports + 1  WHERE id = :elevatorId;");
    $statement1->bindParam(":elevatorId", $elevatorId);

    $statement2 = $dbh->prepare("INSERT INTO DownReports (reporter, comment, elevatorId) VALUES (:email, :comment, :eId);");
    $statement2->bindParam(":email", $userEmail);
    $statement2->bindParam(":comment", $comment);
    $statement2->bindParam(":eId", $elevatorId);

    $statement1->execute();
    $statement2->execute();

    $dbh = null;
}

function getReports($elevatorId) {
    $dbh = connectDB();

    $statement1 = $dbh->prepare("SELECT * FROM DownReports WHERE elevatorId = :eId;");
    $statement1->bindParam(":eId", $elevatorId);
    $statement1->execute();

    $i = 0;
    $arr = [];
    while ($row = $statement1->fetch()) {
        $arr[$i] = $row;
        $i++;
    }

    $dbh = null;

    return $arr;
}

function getReportNumber() {
    $dbh = connectDB();

    $statement1 = $dbh->prepare("SELECT id, downReports, location FROM ElevatorInfo;");

    $statement1->execute();

    $i = 0;
    while ($row = $statement1->fetch()) {
        $arr[$i] = $row;
        $i++;
    }

    $dbh = null;

    return $arr;
}

function isElevatorVerified($elevatorId) {
    $dbh = connectDB();

    $statement1 = $dbh->prepare("SELECT isDownVerified FROM ElevatorInfo WHERE id = :eId;");
    $statement1->bindParam(":eId", $elevatorId);
    $statement1->execute();

    $dbh = null;

    return $statement1->fetchColumn();
}

?>