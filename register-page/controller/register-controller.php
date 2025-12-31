<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}
define('REGISTER_VIEW_PATH', '../view/register-view.php');
include "../../config.php";
include "../model/register-model.php";

class RegisterController
{
    private $registerModel;
    
    public function __construct($dbConn)
    {
        $this->registerModel = new RegisterModel($dbConn);
        
        if (empty($_SESSION['csrf_token'])) 
        {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    function handleRegister()
    {

        
        if(isset($_POST["submit"]))
        {
            
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) 
        {
            $errorMessage = "Invalid CSRF! Please reload page.";
            include REGISTER_VIEW_PATH;
            exit();
        }
            $username = $_POST['username'];
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeatPassword'];
            


            if(empty($username) || empty($firstName) || empty($lastName) || 
               empty($email) || empty($password) || empty($repeatPassword))
            {
                $errorMessage = "Please fill in all fields.";
                include REGISTER_VIEW_PATH;
                exit();
            }

            $result = $this->registerModel->registerUser(
                $username,
                $firstName,
                $lastName,
                $email,
                $password,
                $repeatPassword
            );

            if ($result['success']) 
            {
                $_SESSION["username"] = $username;
                $_SESSION['id'] = $result['user_id'];
                $_SESSION['role_id'] = $result['role_id'];
                header("Location: ../../main-page/main-page.php");
                exit();
            } 
            else 
            {
                $errorMessage = $result['error'];
                include REGISTER_VIEW_PATH;
            }
        }
        else
        {
            include REGISTER_VIEW_PATH;
        }
    }
}

$controller = new RegisterController($dbConn);
$controller->handleRegister();

