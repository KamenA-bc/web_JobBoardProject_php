<?php
    include "../../config.php";
    try
    {
        $sqlCreateTable = "CREATE TABLE IF NOT EXISTS documents (
                            id INT NOT NULL AUTO_INCREMENT,
                            application_id INT NOT NULL,
                            file_path VARCHAR(512) NOT NULL,
                            uploaded_at DATE NOT NULL,
                            PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                
        $dbConn->exec($sqlCreateTable);
        
  
        $sqlAlterTable = "ALTER TABLE documents 
                          ADD CONSTRAINT FK_documents_application 
                          FOREIGN KEY (application_id) 
                          REFERENCES application(id) 
                          ON DELETE CASCADE;";
        
        $dbConn->exec($sqlAlterTable);
        
        echo "Successfully created TABLE documents";
        
    }
    catch (PDOException $e) 
    {
        die("Error on creating the table: " . $e->getMessage());
    } 
?>