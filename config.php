<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "job_board";

try {
    $dbConn = new PDO("mysql:host=$host", $dbUser, $dbPass);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbName";
    $dbConn->exec($sqlCreateDB);

    $sqlUse = "USE $dbName";
    $dbConn->exec($sqlUse);

    if (isset($_SESSION['user_id'])) {
        $stmt = $dbConn->prepare("SET @audit_user_id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>