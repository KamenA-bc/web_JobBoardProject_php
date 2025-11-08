<?php

include '../base-model.php';

class loginModel extends BaseModel
{

    protected $allowedColumns = ['username', 'first_name', 'last_name', 'email', 'passowrd'];

    public function __construct($dbConn)
    {
        $this->table = 'users';
        parent::__construct($dbConn);
    }
    
    public function checkPasswordMatch($username, $password)
    {
        $sql = "SELECT password 
                FROM users 
                WHERE username = :users_username 
                OR email = :users_username";
        try
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(":users_username", $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user === false)
            {
                return [
                    'success' => false,
                    'error' => "User with this username/email does not exist. Please try again."
                ];
            }

            $dbPassword = $user['password'];

            if (!(password_verify($password, $dbPassword))) 
            {
                return [
                    'success' => false,
                    'error' => "Incorrect password. Please try again"
                ];

            } 
            return true;
        }
        catch(PDOException $e)
        {
            return [
                'success' => false,
                'error' => "Database error" . $e 
            ];
        }
    }

    public function loginUser($username, $password)
    {
        if(!$this->checkPasswordMatch($username, $password))
        {
            return false;
        }

        $conditions = [
            'username' => $username,
            'email' => $username
        ];
        
        if($this->rowExists($conditions))
        {
            session_start();
            $_SESSION["username"] = $username;
            header("Location: ../main-page/main-page.php");
            exit();
            return [
            'success' => true,
            ];
        }
        else
        {
            return [
                'success' => false,
                'error' => "No such username or email. Please go and register."
            ];
        }
    }
}