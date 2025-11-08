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
        
        if (empty($data)) {
            return false;
        }
        
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        
        $stmt = $this->dbConn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $values = array_values($data);
        $success = $stmt->execute($values);
        
        if ($success) {
            return $this->dbConn->lastInsertId();
        }
        
        return false;
    }
}
