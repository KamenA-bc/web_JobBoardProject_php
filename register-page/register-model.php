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
            'role_id' => 2 //User role_id
        ];

        try 
        {
            $userId = $this->insertRow($data);

            if ($userId) 
            {
                return [
                    'success' => true,
                    'user_id' => $userId,
                    'message' => "Successful registration!"
                ];
            }

            return [
                'success' => false,
                'error' => "Failed to create account. Please try again."
            ];
        } 
        catch (PDOException $e) 
        {
            return [
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ];
        }
    }
}


