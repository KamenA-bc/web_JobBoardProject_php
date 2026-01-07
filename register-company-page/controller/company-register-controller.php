<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../config.php";
include "../model/company-model.php";
DEFINE('COMPANY_REGISTER_PATH','../view/company-register-view.php');
DEFINE('UNAUTHORIZED_PATH', '../../transition-views/unauthorized.php');

class CompanyRegisterController
{
    private $companyModel;
    const ROWS_PER_PAGE = 5;
    
    public function __construct($dbConn) 
    {
        $this->companyModel = new CompanyModel($dbConn);
        if (empty($_SESSION['csrf_token'])) {
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
                $this->loadViewData($errorMessage);
                exit();
            }

            if (isset($_POST['submit_register'])) {
                $this->handle_register();
            } elseif (isset($_POST['delete_company_id'])) {
                $this->handle_delete();
            }
        } 
        else 
        {
            $this->loadViewData();
        }
    }


    private function loadViewData($errorMessage = null, $successMessage = null)
    {
        $companies = [];
        $pages = 0;

        if (isset($_SESSION['user_id'])) {
            $page = isset($_GET['page-nr']) ? (int)$_GET['page-nr'] : 1;
            $start = ($page - 1) * self::ROWS_PER_PAGE;

            $companies = $this->companyModel->getCompaniesByOwnerPaginated($_SESSION['user_id'], $start, self::ROWS_PER_PAGE);
            $totalRows = $this->companyModel->getCompanyCountByOwner($_SESSION['user_id']);
            $pages = ceil($totalRows / self::ROWS_PER_PAGE);
        }

        include COMPANY_REGISTER_PATH;
    }

    private function handle_register()
    {
        $companyName = $_POST["companyName"];
        $companyURL = $_POST["companyURL"];

        if(empty($companyName) || empty($companyURL)) {
            $this->loadViewData("Please fill in all fields.");
            exit();
        }
        
        if(empty($_SESSION["user_id"])) {
            $this->loadViewData("Please login/register to register a company");
            exit();
        }

        $result = $this->companyModel->registerCompany($companyName, $companyURL);   

        if ($result['success']) {
            $this->loadViewData(null, $result['message']);
        } else {
            $this->loadViewData($result['error']);
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
            $this->loadViewData($result['error']);
        } else {
            $this->loadViewData(null, $result['message']);
        }
    }
}

$controller = new CompanyRegisterController($dbConn);
$controller->handleRequest();
?>