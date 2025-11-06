<?php

        include "../config.php";
        include "company-register-model.php";
        
        $comapnyRegisterModel = new companyRegisterModel($dbConn);
        
        if(isset($_POST["submit"]))
        {
            $companyName = $_POST["companyName"];
            $companyURL = $_POST["companyURL"];
            
            $comapnyRegisterModel->registerCompany($companyName, $companyURL);  
        }
