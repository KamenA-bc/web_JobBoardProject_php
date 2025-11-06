<?php

class loginModel
{

    private PDO $dbConn;

    public function __construct(PDO $dbConn)
    {
        $this->dbConn = $dbConn;
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
                $errorMessage = "User with this username/email does not exist. Please try again.";
                include "login-view.php";
                return false;
            }

            $dbPassword = $user['password'];

            if (!(password_verify($password, $dbPassword))) 
            {
                $errorMessage = "Incorrect password. Please try again";
                include "login-view.php";
                return false;
            } 
            return true;
        }
        catch(PDOException $e)
        {
            $errorMessage = "Database error" . $e ;
            include "login-view.php";
            return false;
        }
    }

    public function loginUser($username, $password)
    {
        if(!$this->checkPasswordMatch($username, $password))
        {
            return false;
        }

        $sql = "SELECT * FROM users WHERE username = :users_username OR email = :users_username";
        try
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(":users_username", $username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["username"] = $user["username"];
            header("Location: ../main-page/main-page.php");
            exit();
            return true;
        }
        catch(PDOException $e)
        {   
            $errorMessage = "Database error:" . $e;
            include "login-view.php";
            return false;
        }
    }
}