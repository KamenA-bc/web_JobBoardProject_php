<?php
    include "../../config.php";
    try
    {
        $sqlCreateTable =   "CREATE TABLE IF NOT EXISTS company  (
                            id INT NOT NULL AUTO_INCREMENT,
                            name VARCHAR(128) NOT NULL UNIQUE,
                            site_url VARCHAR(2048) NOT NULL,
                            PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                
        $dbConn->exec($sqlCreateTable);
        
        echo "Successfully create TABLE company";
        
    }
    catch (PDOException $e) 
    {
        die("Error on creating the table:" . $e->getMessage());
    } 
