<?php
session_start();

include "../config.php";
include "login-model.php";

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
                include 'login-view.php';
                exit();
            }

            $result = $this->loginModel->loginUser($username, $password);

            if ($result['success']) 
            {
                $_SESSION['username'] = $result['user']['username'];
                $_SESSION['id'] = $result['user']['id'];
                $_SESSION['role_id'] = $result['user']['role_id'];
                header("Location: ../main-page/main-page.php");
                exit();
            } 
            else 
            {
                $errorMessage = $result['error'];
                include 'login-view.php';
            }
        }
        else
        {
            include 'login-view.php';
        }
    }
}

$controller = new LoginController($dbConn);
$controller->handleLogin();