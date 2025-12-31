<?php
    include "../../config.php";
    try
    {
        $sqlCreateTable =   "CREATE TABLE IF NOT EXISTS users (
                            id INT NOT NULL AUTO_INCREMENT,
                            username VARCHAR(128) NOT NULL UNIQUE,
                            first_name VARCHAR(64) NOT NULL,
                            last_name VARCHAR(64) NOT NULL,
                            email VARCHAR(128) NOT NULL UNIQUE,
                            password VARCHAR(256) NOT NULL,
                            role_id INT NOT NULL,
                            PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                
        $dbConn->exec($sqlCreateTable);
        
        $sqlAlterTable = "ALTER TABLE users 
                          ADD CONSTRAINT FK_users_roles FOREIGN KEY ( role_id ) REFERENCES roles(id) ;";
        
        $dbConn->exec($sqlAlterTable);
        
        echo "Successfully create TABLE users";
        
    }
    catch (PDOException $e) 
    {
        die("Error on creating the table:" . $e->getMessage());
    } 


