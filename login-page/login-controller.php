<?php
        include "../config.php";
        include "login-model.php";
        
        $logingModel = new loginModel($dbConn);
        
    if(isset($_POST["submit"]))
    {
        $username = $_POST["username"];
        $password= $_POST["password"];
        
        $logingModel->loginUser($username, $password);
    }
