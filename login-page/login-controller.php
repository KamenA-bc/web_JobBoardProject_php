<?php
session_start(); // Start session once at the top

include "../config.php";
include "login-model.php";

$loginModel = new loginModel($dbConn);

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
    
    $result = $loginModel->loginUser($username, $password);

    if ($result['success']) 
    {
        $_SESSION["username"] = $result['user']['username'];
        $_SESSION['id'] = $result['user']['id'];
        
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
