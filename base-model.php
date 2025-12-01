<?php

abstract class BaseModel
{
    protected PDO $dbConn;
    protected $table;
    
        public function __construct(PDO $dbConn) 
    {
        $this->dbConn = $dbConn;
    }
    

    protected function insertRow(array $data)
    {
        if (empty($data)) 
        {
            return false;
        }
        
        $columns = array_keys($data);
        $placeholders = [];
        
        foreach ($columns as $column) 
        {
            $placeholders[] = ":$column";
        }   
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        
        $stmt = $this->dbConn->prepare($sql);
        
        if (!$stmt) 
        {
            return false;
        }
        
        
        foreach ($data as $column => $value) 
        {
            $stmt->bindValue(":$column", $value);
        }
     
        if ($stmt->execute()) {
            return $this->dbConn->lastInsertId();
        }
        
        return false;
    }
    
    protected function rowExists(array $conditions)
    {
        if (empty($conditions)) 
        {
            return false;
        }
        $whereClauses = [];
        foreach ($conditions as $column => $value) 
        {
            $whereClauses[] = "$column = :$column";
        }
        
        $whereString = implode(' OR ', $whereClauses);

        $sql = sprintf(
            "SELECT * FROM %s WHERE %s",
            $this->table,
            $whereString
        );

        try 
        {
            $stmt = $this->dbConn->prepare($sql);

            foreach ($conditions as $column => $value) 
            {
                $stmt->bindValue(":$column", $value);
            }

            $stmt->execute();
            
             $row = $stmt->fetch(PDO::FETCH_ASSOC);
             
             return $row;
            
        } 
        catch (PDOException $e) 
        {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    protected function deleteRow($rowID)
    {
        $sql = sprintf(
                "DELETE FROM %s WHERE id = :id",
                $this->table,
        );
        try
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':id', $rowID, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        }
        catch (PDOException $e)
        {
            error_log("Database error in deleteRow: " . $e->getMessage());
            return false;
        }
    }
    
    protected function selectAll() 
    {
        $sql = "SELECT * FROM {$this->table}";
        try 
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->execute();
            
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
            return $rows;
        } 
        catch (PDOException $e) 
        {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
}