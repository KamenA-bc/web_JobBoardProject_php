<?php
include "../../config.php";
include "../model/login-model.php";

DEFINE('VIEW_PATH','../view/forgotten-password-view.php');

class ForgottenPassController
{
    private $loginModel;
    
    public function __construct($dbConn) 
    {
        $this->loginModel = new LoginModel($dbConn);
        
                        if (empty($_SESSION['csrf_token'])) 
        {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    public function resetPassword()
    {
        $errorMessage = "";
        $successMessage = "";
        
        if(isset($_POST["submit"]))
        {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) 
            {
                $errorMessage = "Invalid CSRF! Please reload page.";
                include LOGING_VIEW_PATH;
                exit();
            }
            
            $username = $_POST["username_or_email"];
            $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            if(empty($username) || empty($hashedPassword))
            {
                $errorMessage = "Please fill in all fields.";
                include (VIEW_PATH);
                exit();
            }
            
            $result = $this->loginModel->resetPassword($username, $hashedPassword);

            if ($result['success']) 
            {
                $successMessage = $result['message'];
                include (VIEW_PATH);
                exit();
                unset($_POST);
            } 
            else 
            {
                $errorMessage = $result['error'];
                include (VIEW_PATH);
                exit();
            }
        }
        else
        {
            include (VIEW_PATH);
        }
    }
}

$controller = new ForgottenPassController($dbConn);
$controller->resetPassword();