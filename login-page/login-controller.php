<?php
        include "../config.php";
        include "login-model.php";
        
        $logingModel = new loginModel($dbConn);
        
    if(isset($_POST["submit"]))
    {
        $result = $logingModel->loginUser(
        $_POST["username"],
        $_POST["password"]
        );

        if (!$result['success']) 
        {
            $errorMessage = $result['error'];
            include 'login-view.php';
        } 
    }
