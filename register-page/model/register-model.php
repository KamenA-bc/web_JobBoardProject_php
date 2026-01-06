<?php
include '../../base-model.php';

include_once 'validator.php'; 

define('ROLE_ADMIN', 1);
define('ROLE_USER', 2);

class RegisterModel extends BaseModel
{
    public function __construct(PDO $dbConn)
    {
        $this->table = 'users';
        parent::__construct($dbConn);
    }
       

    public function registerUser($username, $firstName, $lastName, $email, $password, $repeatPassword)
    {
        if (!Validator::isValidEmail($email)) 
        {
            return [
                'success' => false,
                'error' => "Invalid email address. Please enter a valid email address."
            ];
        }

        if (!Validator::doPasswordsMatch($password, $repeatPassword)) 
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
            'role_id' => ROLE_USER 
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
?>