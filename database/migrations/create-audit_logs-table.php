<?php
include "../../config.php";

try 
{
    $sqlCreateTable = "CREATE TABLE IF NOT EXISTS audit_logs (
                        id INT NOT NULL AUTO_INCREMENT,
                        user_id INT NOT NULL,
                        action VARCHAR(10) NOT NULL,
                        entity VARCHAR(32) NOT NULL,
                        entity_id INT NOT NULL,
                        created_at DATETIME NOT NULL,
                        PRIMARY KEY (id)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    
    $dbConn->exec($sqlCreateTable);
    echo "Table 'audit_logs' verified.<br>";

    try 
    {
        $sqlAlterTable = "ALTER TABLE audit_logs 
                          ADD CONSTRAINT FK_audit_user_id 
                          FOREIGN KEY (user_id) REFERENCES users(id)";
        $dbConn->exec($sqlAlterTable);
        echo "Foreign Key constraint added.<br>";
    } catch (PDOException $e) 
    {

    }

    
    $query = $dbConn->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_COLUMN);

    $excludedTables = ['audit_logs']; 

    echo "<hr><h3>Generating Triggers...</h3>";

    foreach ($tables as $table) 
    {
        if (in_array($table, $excludedTables)) {
            continue;
        }

        $trigInsert = "audit_{$table}_insert";
        $trigUpdate = "audit_{$table}_update";
        $trigDelete = "audit_{$table}_delete";


        $dbConn->exec("DROP TRIGGER IF EXISTS $trigInsert");
        $sqlInsert = "
            CREATE TRIGGER $trigInsert
            AFTER INSERT ON $table
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, entity, entity_id, created_at)
                VALUES (
                    COALESCE(@audit_user_id, 1), 
                    'INSERT', 
                    '$table', 
                    NEW.id, 
                    NOW()
                );
            END";
        $dbConn->exec($sqlInsert);

        $dbConn->exec("DROP TRIGGER IF EXISTS $trigUpdate");
        $sqlUpdate = "
            CREATE TRIGGER $trigUpdate
            AFTER UPDATE ON $table
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, entity, entity_id, created_at)
                VALUES (
                    COALESCE(@audit_user_id, 1), 
                    'UPDATE', 
                    '$table', 
                    NEW.id, 
                    NOW()
                );
            END";
        $dbConn->exec($sqlUpdate);


        $dbConn->exec("DROP TRIGGER IF EXISTS $trigDelete");
        $sqlDelete = "
            CREATE TRIGGER $trigDelete
            AFTER DELETE ON $table
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, entity, entity_id, created_at)
                VALUES (
                    COALESCE(@audit_user_id, 1), 
                    'DELETE', 
                    '$table', 
                    OLD.id, 
                    NOW()
                );
            END";
        $dbConn->exec($sqlDelete);

        echo "Attached triggers to: <strong>$table</strong><br>";
    }

    echo "<hr><strong>SUCCESS: Full audit system installed on all tables.</strong>";

} catch (PDOException $e) {
    die("<br><br><strong>Fatal Error:</strong> " . $e->getMessage());
} 
?>