<?php
    if (session_status() === PHP_SESSION_NONE) 
    {
        session_start();
    }
include "../../config.php";
include "../model/company-model.php";
DEFINE('COMPANY_REGISTER_PATH','../view/company-register-view.php');
DEFINE('UNAUTHORIZED_PATH', '../../transition-views/unauthorized.php');

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

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) 
            {
                $errorMessage = "Invalid CSRF! Please reload page.";
                $companies = $this->companyModel->getCompaniesByOwner($_SESSION['user_id'] ?? 0);
                include COMPANY_REGISTER_PATH;
                exit();
            }

            if (isset($_POST['submit_register'])) 
            {
                $this->handle_register();
            } 
            elseif (isset($_POST['delete_company_id'])) 
            {
                $this->handle_delete();
            }
        } 
        else 
        {
            if(isset($_SESSION['user_id'])) 
            {
                $companies = $this->companyModel->getCompaniesByOwner($_SESSION['user_id']);
            }
            include COMPANY_REGISTER_PATH;
        }
    }

    private function handle_register()
    {
        $companyName = $_POST["companyName"];
        $companyURL = $_POST["companyURL"];

        if(empty($companyName) || empty($companyURL))
        {
            $errorMessage = "Please fill in all fields.";
            $companies = $this->companyModel->getCompaniesByOwner($_SESSION['user_id']);
            include COMPANY_REGISTER_PATH;
            exit();
        }
        
        if(empty($_SESSION["user_id"]))
        {
            $errorMessage = "Please login/register to register a company";
            include COMPANY_REGISTER_PATH;
            exit();
        }

        $result = $this->companyModel->registerCompany(
            $companyName,
            $companyURL
        );   

        $companies = $this->companyModel->getCompaniesByOwner($_SESSION['user_id']);

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
    
    private function handle_delete()
    {
        if (empty($_SESSION["user_id"])) {
            header("Location: ../../transition-views/login.php"); 
            exit();
        }

        $companyId = $_POST['delete_company_id'];
        $userId = $_SESSION['user_id'];

        $result = $this->companyModel->deleteCompany($companyId, $userId);

        if (!$result['success']) {
            if (isset($result['status']) && $result['status'] === 'unauthorized') {
                header("Location: " . UNAUTHORIZED_PATH);
                exit();
            }
            
            $errorMessage = $result['error'];
        } else {
            $successMessage = $result['message'];
        }

        $companies = $this->companyModel->getCompaniesByOwner($_SESSION['user_id']);
        include COMPANY_REGISTER_PATH; 
    }
}

$controller = new CompanyRegisterController($dbConn);
$controller->handleRequest();
?>