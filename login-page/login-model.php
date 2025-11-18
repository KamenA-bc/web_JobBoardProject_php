<?php

include '../base-model.php';

class loginModel extends BaseModel
{
    public function __construct(PDO $dbConn)
    {
        $this->table = 'users';
        parent::__construct($dbConn);
    }
    
    
    private function checkPasswordMatch($username, $password)
    {
        $sql = "SELECT password 
                FROM users 
                WHERE username = :username 
                OR email = :username";
        try
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user === false)
            {
                return false;
            }

            $dbPassword = $user['password'];

            if (!password_verify($password, $dbPassword)) 
            {
                return false;
            } 
            
            return true;
        }
        catch(PDOException $e)
        {
            error_log("Database error in checkPasswordMatch: " . $e->getMessage());
            return false;
        }
    }

    public function loginUser($username, $password)
    {
        if(!$this->checkPasswordMatch($username, $password))
        {
            return [
                'success' => false,
                'error' => "Invalid username or password."
            ];
        }

        $conditions = [
            'username' => $username,
            'email' => $username
        ];
        
        if($user = $this->rowExists($conditions))
        {
            return [
                'success' => true,
                'user' => $user
            ];
        }
        else
        {
            return [
                'success' => false,
                'error' => "Invalid username or password."
            ];
        }
    }
}