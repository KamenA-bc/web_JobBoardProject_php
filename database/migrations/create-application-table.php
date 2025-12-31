<?php
    include "../../config.php";
    try
    {
        $sqlCreateTable =   "CREATE TABLE IF NOT EXISTS application (
                            id INT NOT NULL AUTO_INCREMENT,
                            position_id INT NOT NULL,
                            user_id INT NOT NULL,
                            applied_on DATE NOT NULL,
                            status_id INT NOT NULL,
                            PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                
        $dbConn->exec($sqlCreateTable);
        
        $sqlAlterTable = "ALTER TABLE application 
                          ADD CONSTRAINT FK_application_position FOREIGN KEY ( position_id ) REFERENCES positions(id) ;
                          
                         ALTER TABLE application 
                          ADD CONSTRAINT FK_application_user FOREIGN KEY ( user_id ) REFERENCES users(id) ;
                          
                        ALTER TABLE application 
                          ADD CONSTRAINT FK_application_status FOREIGN KEY ( status_id ) REFERENCES application_status(id) ;";
        
        
        $dbConn->exec($sqlAlterTable);
        
        echo "Successfully create TABLE users";
        
    }
    catch (PDOException $e) 
    {
        die("Error on creating the table:" . $e->getMessage());
    } 
