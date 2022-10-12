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
?>