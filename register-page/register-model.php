<?php

class registerModel
{
    private PDO $dbConn;

    public function __construct(PDO $dbConn)
    {
        $this->dbConn = $dbConn;
    }
    public function userExists($username, $email) 
    {
        $sql = "SELECT id FROM users 
                WHERE username = :users_username 
                OR email = :users_email";
        try 
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':users_username', $username);
            $stmt->bindParam(':users_email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user !== false;
        } 
        catch (PDOException $e) 
        {
            include 'register-view.php';
            echo "Database error: " . $e->getMessage();
            return false;
        }
    }

    public function validateEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
        {
            return false;
        }
        return true;
    }

    public function passwordMatch($password, $repeatPassword)
    {
        if(!($password == $repeatPassword))
        {
            return false;
        }
        return true;
    }

    public function registerUser($username, $firstName, $lastName, $email, $password, $repeatPassword)
    {

        if (!$this->validateEmail($email)) 
        {
            $errorMessage = "Invalid email address. Please enter a valid email address.";
            include 'register-view.php';
            return false;
        }


        if (!$this->passwordMatch($password, $repeatPassword)) 
        {
            $errorMessage = "Passwords don't match. Please try again.";
            include 'register-view.php';
            return false;
        }


        if ($this->userExists($username, $email)) 
        {
            $errorMessage = "This username or email already exists.";
            include 'register-view.php';
            return false;
        }


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try 
        {
            $sql = "INSERT INTO users (username, first_name, last_name, email, password)
                    VALUES (:users_username, :users_first_name, :users_last_name, :users_email, :users_password)";
            
            $stmt = $this->dbConn->prepare($sql);

            $stmt->bindParam(":users_username", $username);
            $stmt->bindParam(":users_first_name", $firstName);
            $stmt->bindParam(":users_last_name", $lastName);
            $stmt->bindParam(":users_email", $email);
            $stmt->bindParam(":users_password", $hashedPassword);

            $stmt->execute();

            $successMessage = "Successful registration!";
            include 'register-view.php';
            return true;
        } 
        catch (PDOException $e) 
        {
            $errorMessage = "Database error: " . $e->getMessage();
            include 'register-view.php';
            return false;
        }
    }
}


