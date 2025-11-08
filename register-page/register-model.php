<?php
include '../base-model.php';

class registerModel extends BaseModel
{
    protected $allowedColumns = ['username', 'first_name', 'last_name', 'email', 'password', 'role_id'];
    
    public function __construct(PDO $dbConn)
    {
        $this->table = 'users';
        parent::__construct($dbConn);
    }
    
        protected function filterColumns(array $data)
    {
        return array_intersect_key($data, array_flip($this->allowedColumns));
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
        
                $data = [
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $hashedPassword,
            'role_id' => 2
        ];

        try 
        {
            $this->insertRow($data);
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


