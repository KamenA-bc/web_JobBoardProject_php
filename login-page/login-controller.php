<?php
        include "../config.php";
        include "login-model.php";
        
        $logingModel = new loginModel($dbConn);
        
    if(isset($_POST["submit"]))
    {
        $result = $logingModel->loginUser(
        $username = $_POST["username"],
        $password= $_POST["password"]
        );

        if ($result['success']) 
        {
            $successMessage = $result['message'];
            include 'login-view.php';
        } 
        else 
        {
            $errorMessage = $result['error'];
            include 'login-view.php';
        }
    }
