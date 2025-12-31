<?php
    if (session_status() === PHP_SESSION_NONE) 
    {
        session_start();
    }
include "../../config.php";
include "../model/company-model.php";


class CompanyEditController
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_company'])) 
        {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) 
        {
            $errorMessage = "Invalid CSRF! Please reload page.";
            include '../view/company-update-view.php';
            exit();
        }
            $this->processUpdate();
        }
        elseif (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) 
        {
            $this->showEditForm($_GET['id']);
        }
        else 
        {
            $this->listCompanies();
        }
    }
    
private function processUpdate()
    {
        $id = $_POST['id'];

        $data = [
            'name' => $_POST['name'],
            'site_url' => $_POST['site_url']
        ];

        $success = $this->companyModel->updateRow($data, $id);

        if ($success) 
        {
            $companyData = $this->companyModel->selectById($id);
            include '../view/company-edit-view.php';
            exit();
        } 
        else 
        {
            $errorMessage = "Error updating record.";
            $companyData = $this->companyModel->selectById($id);
            include '../view/company-update-view.php';
            exit();
        }
    }
    
    private function showEditForm($id)
    {
        if (!isset($_SESSION['user_id'])) {
        header("Location: ../../login-page/view/login-view.php"); 
        exit();
    }
        $companyData = $this->companyModel->selectById($id);
        $ownerId = $companyData['owner_id'];
        if($_SESSION['user_id'] == $ownerId || $_SESSION['role_id'] == 1)
        {
            if (!$companyData) 
            {
                return;
            }
            include '../view/company-update-view.php';
        }
        else
        {
            header("Location: ../../transition-views/unauthorized-page.php");
            exit();
        }
    }

    private function listCompanies()
        {
            $start = 0;
            if(isset($_GET['page-nr']))
            {
                $page = $_GET['page-nr'] - 1;
                $start = $page * ROWS_PER_PAGE;
            }
            $companies = $this->companyModel->selectWithLimit($start);
            $rows = $this->companyModel->selectAll();
            $number_of_rows = count($rows);
            $pages = ceil($number_of_rows / ROWS_PER_PAGE);

            include '../view/company-edit-view.php';
            exit();
        }
}

$controller = new CompanyEditController($dbConn);
$controller->handleRequest();

