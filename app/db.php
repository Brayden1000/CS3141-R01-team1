<?php
function connectDB() {
    try {
        $config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected Successfully";
        return $dbh;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function addUser($email) {
    $dbh = connectDB();
    $statement1 = $dbh->prepare("INSERT INTO UserData (email, isVerified) VALUES (:email, 0)");
    $statement1->bindParam(":email", $email);
    $statement1->execute();

    $dbh = null;
}

function verifyUser($email) {
    $dbh = connectDB();
    $statement1 = $dbh->prepare("UPDATE UserData SET isVerified = 1 WHERE email = :email;");
    $statement1->bindParam(":email", $email);
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

    $statement1 = $dbh->prepare("UPDATE ElevatorInfo SET downReports += 1  WHERE elevatorId = :elevatorId;");
    $statement1->bindParam(":elevatorId", $elevatorId);

    $statement2 = $dbh->prepare("INSERT INTO DownReports (email, isVerified) VALUES (:email, 0)");
}
?>