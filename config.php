<?php

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
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>