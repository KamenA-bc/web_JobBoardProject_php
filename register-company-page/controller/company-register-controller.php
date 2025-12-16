<?php
session_start();
include "../../config.php";
include "../model/company-model.php";
DEFINE('COMPANY_REGISTER_PATH','../view/company-register-view.php');

class CompanyRegisterController
{
    private $companyModel;
    
    public function __construct($dbConn) 
    {
        $this->companyModel = new CompanyModel($dbConn);
        
        if (empty($_SESSION['csrf_token'])) 
        {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    public function handle_register()
    {
        if(isset($_POST["submit"]))
        {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) 
        {
            $errorMessage = "Invalid CSRF! Please reload page.";
            header('../view/company-register-view.php');
            exit();
        }
            $companyName = $_POST["companyName"];
            $companyURL = $_POST["companyURL"];

            if(empty($companyName) || empty($companyURL))
            {
                $errorMessage = "Please fill in all fields.";
                include COMPANY_REGISTER_PATH;
                exit();
            }

            $result = $this->companyModel->registerCompany(
                $companyName,
                $companyURL
            );   

            if ($result['success']) 
            {
                $successMessage = $result['message'];
                include COMPANY_REGISTER_PATH;
            } 
            else 
            {
                $errorMessage = $result['error'];
                include COMPANY_REGISTER_PATH;
            }
        }
        else
        {
            include COMPANY_REGISTER_PATH;
        }
    }
}

$controller = new CompanyRegisterController($dbConn);
$controller->handle_register();
