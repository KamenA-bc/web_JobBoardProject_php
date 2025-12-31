<?php
include "../../config.php";

try {
    $sqlCreateTable = "
        CREATE TABLE IF NOT EXISTS roles (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(64) NOT NULL UNIQUE,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";

    $dbConn->exec($sqlCreateTable);
    echo "Table successfully created<br>";

} catch (PDOException $e) {
    die("Error on creating the table: " . $e->getMessage());
}
?>