<?php
session_start();

include "../config.php";
include "register-model.php";

class RegisterController
{
    private $registerModel;
    
    public function __construct($dbConn)
    {
        $this->registerModel = new RegisterModel($dbConn);
    }
    function handleRegister()
    {
        if(isset($_POST["submit"]))
        {
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
                include 'register-view.php';
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
                header("Location: ../main-page/main-page.php");
                exit();
            } 
            else 
            {
                $errorMessage = $result['error'];
                include 'register-view.php';
            }
        }
        else
        {
            include 'register-view.php';
        }
    }
}

$controller = new RegisterController($dbConn);
$controller->handleRegister();

