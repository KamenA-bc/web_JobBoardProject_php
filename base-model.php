<?php

abstract class BaseModel
{
    protected PDO $dbConn;
    protected $table;
    
        public function __construct(PDO $dbConn) 
    {
        $this->dbConn = $dbConn;
    }
    
    public function insertRow(array $data)
    {
        if (empty($data)) {
            return false;
        }
        $data = $this->filterColumns($data);
        
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
    
    public function rowExists(array $conditions)
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

        try {
            $stmt = $this->dbConn->prepare($sql);

            foreach ($conditions as $column => $value) 
            {
                $stmt->bindValue(":$column", $value);
            }

            $stmt->execute();
            
            return $stmt->fetch() !== false;
            
        } catch (PDOException $e) {
            return false;
        }
    }
}