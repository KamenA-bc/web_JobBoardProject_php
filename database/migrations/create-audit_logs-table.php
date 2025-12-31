<?php
    include "../../config.php";
    try
    {
        $sqlCreateTable =   "CREATE TABLE IF NOT EXISTS audit_logs (
                            id INT NOT NULL AUTO_INCREMENT,
                            user_id INT NOT NULL,
                            entity VARCHAR(32) NOT NULL,
                            entity_id INT NOT NULL,
                            created_at DATE NOT NULL,
                            PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                
        $dbConn->exec($sqlCreateTable);
        
        $sqlAlterTable = "ALTER TABLE audit_logs 
                          ADD CONSTRAINT FK_audit_user_id FOREIGN KEY ( user_id ) REFERENCES users(id) ;";
        
        $dbConn->exec($sqlAlterTable);
        
        echo "Successfully create TABLE users";
        
    }
    catch (PDOException $e) 
    {
        die("Error on creating the table:" . $e->getMessage());
    } 
