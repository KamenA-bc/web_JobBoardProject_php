<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}
DEFINE('LOGING_VIEW_PATH','../view/login-view.php');
include "../../config.php";
include "../model/login-model.php";

class LoginController
{
    private $loginModel;
    
    public function __construct($dbConn) 
    {
        $this->loginModel = new LoginModel($dbConn);
    }
    public function handleLogin()
    {
        if(isset($_POST["submit"]))
        {
            $username = $_POST["username"];
            $password = $_POST["password"];

            if(empty($username) || empty($password))
            {
                $errorMessage = "Please fill in all fields.";
                include LOGING_VIEW_PATH;
                exit();
            }

            $result = $this->loginModel->loginUser($username, $password);

            if ($result['success']) 
            {
                $_SESSION['username'] = $result['user']['username'];
                $_SESSION['user_id'] = $result['user']['id'];
                $_SESSION['role_id'] = $result['user']['role_id'];
                header("Location: ../../main-page/main-page.php");
                exit();
            } 
            else 
            {
                $errorMessage = $result['error'];
                include LOGING_VIEW_PATH;
            }
        }
        else
        {
            include LOGING_VIEW_PATH;
        }
    }
}

$controller = new LoginController($dbConn);
$controller->handleLogin();