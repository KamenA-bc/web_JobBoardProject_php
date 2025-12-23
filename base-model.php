<?php
    define('START', 0);
    define('ROWS_PER_PAGE', 5);
class BaseModel
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
    
    public function selectAll() 
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
    
    public function selectById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id=:id";
        try
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
    
    public function updateRow(array $data, $id)
    {
        if (empty($data) || empty($id)) 
        {
            return false;
        }

        $setClauses = [];
        $parameters = [];

        foreach ($data as $column => $value) 
        {
            $setClauses[] = "$column = :set_$column";

            $parameters[":set_$column"] = $value;
        }

        $parameters[':id'] = $id;
        $setString = implode(', ', $setClauses);

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->table,
            $setString
        );

        try 
        {
            $stmt = $this->dbConn->prepare($sql);

            foreach ($parameters as $param => $value) 
            {
                $stmt->bindValue($param, $value);
            }

            return $stmt->execute();

        } 
        catch (PDOException $e) 
        {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
        public function selectWithLimit($start)
    {
        $sql = "SELECT * FROM {$this->table} LIMIT :start, :rows";
        $stmt = $this->dbConn->prepare($sql);

        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':rows', ROWS_PER_PAGE, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}