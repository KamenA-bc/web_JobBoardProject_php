<?php

        include "../config.php";
        include "company-register-model.php";
        
        $comapnyRegisterModel = new companyRegisterModel($dbConn);
        
        if(isset($_POST["submit"]))
        {
            $result = $comapnyRegisterModel->registerCompany(
            $_POST["companyName"],
            $_POST["companyURL"]
        );   
            if ($result['success']) 
            {
                $successMessage = $result['message'];
                include 'company-register-view.php';
            } 
            else 
            {
                $errorMessage = $result['error'];
                include 'company-register-view.php';
            }
        }
