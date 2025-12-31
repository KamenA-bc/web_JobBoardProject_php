<?php
    include "../../config.php";
    try
    {
        $sqlCreateTable =   "CREATE TABLE IF NOT EXISTS positions  (
                            id INT NOT NULL AUTO_INCREMENT,
                            company_id INT NOT NULL,
                            title_id INT NOT NULL,
                            location VARCHAR(2048) NOT NULL,
                            seniority_id INT NOT NULL,
                            status_id INT NOT NULL,
                            PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                
        $dbConn->exec($sqlCreateTable);
        
        $sqlAlterTable = "ALTER TABLE positions 
                          ADD CONSTRAINT FK_positions_company FOREIGN KEY ( company_id ) REFERENCES company(id) ;
                          
                          ALTER TABLE positions
                          ADD CONSTRAINT FK_positions_title FOREIGN KEY (title_id) REFERENCES title(id);
                          
                          ALTER TABLE positions
                          ADD CONSTRAINT FK_positions_seniority FOREIGN KEY (seniority_id) REFERENCES seniority(id);
                          
                          ALTER TABLE positions
                          ADD CONSTRAINT FK_positions_status_id FOREIGN KEY (status_id) REFERENCES position_status(id)";
        
        $dbConn->exec($sqlAlterTable);
        
        echo "Successfully create TABLE company";
        
    }
    catch (PDOException $e) 
    {
        die("Error on creating the table:" . $e->getMessage());
    } 