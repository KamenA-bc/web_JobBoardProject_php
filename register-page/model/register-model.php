<?php
include '../../base-model.php';

define('ROLE_ADMIN', 1);
define('ROLE_USER', 2);

class RegisterModel extends BaseModel
{
    public function __construct(PDO $dbConn)
    {
        $this->table = 'users';
        parent::__construct($dbConn);
    }
       
    private function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function passwordMatch($password, $repeatPassword)
    {
        return $password === $repeatPassword;
    }


    public function registerUser($username, $firstName, $lastName, $email, $password, $repeatPassword)
    {

        if (!$this->validateEmail($email)) 
        {
            return [
                'success' => false,
                'error' => "Invalid email address. Please enter a valid email address."
            ];
        }


        if (!$this->passwordMatch($password, $repeatPassword)) 
        {
            return [
                'success' => false,
                'error' => "Passwords don't match. Please try again."
            ];
        }

        $conditions = [
            'username' => $username,
            'email' => $email
        ];
        
        if ($this->rowExists($conditions)) 
        {
            return [
                'success' => false,
                'error' => "This username or email already exists."
            ];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $hashedPassword,
            'role_id' => ROLE_USER // User role_id
        ];

        try 
        {
            $userId = $this->insertRow($data);
            
            if($userId)
            {
                return [
                    'success' => true,
                    'user_id' => $userId,
                    'role_id' => ROLE_USER,
                    'message' => "Account created successfully!"
                ];
            }
            else
            {
                return [
                    'success' => false,
                    'error' => "Failed to create account. Please try again."
                ];
            }
        } 
        catch (PDOException $e) 
        {
            error_log("Registration error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => "Failed to create account. Please try again."
            ];
        }
    }
}


