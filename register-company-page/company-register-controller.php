<?php
include "../config.php";
include "company-model.php";


class CompanyRegisterController
{
    private $companyModel;
    
    public function __construct($dbConn) 
    {
        $this->companyModel = new CompanyModel($dbConn);
    }
    public function handle_register()
    {
        if(isset($_POST["submit"]))
        {
            $companyName = $_POST["companyName"];
            $companyURL = $_POST["companyURL"];

            if(empty($companyName) || empty($companyURL))
            {
                $errorMessage = "Please fill in all fields.";
                include 'company-register-view.php';
                exit();
            }

            $result = $this->companyModel->registerCompany(
                $companyName,
                $companyURL
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
        else
        {
            include 'company-register-view.php';
        }
    }
}

$controller = new CompanyRegisterController($dbConn);
$controller->handle_register();
