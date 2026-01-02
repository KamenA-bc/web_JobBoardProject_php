<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

include "../../config.php";
include "../model/application-model.php";

class MyApplicationsController
{
    private $appModel;
    const ROWS_PER_PAGE = 5;
    
    public function __construct($dbConn)
    {
        $this->appModel = new ApplicationModel($dbConn);
    }

    public function handleRequest()
    {
        if (empty($_SESSION['user_id'])) 
        {
            header("Location: ../../login-page/view/login-view.php");
            exit();
        }
        $this->listApplications();
    }
    
    private function listApplications()
    {
        $selectedCompany = isset($_GET['company']) && $_GET['company'] !== '' ? $_GET['company'] : null;

        $page = isset($_GET['page-nr']) ? (int)$_GET['page-nr'] : 1;
        $start = ($page - 1) * self::ROWS_PER_PAGE;

        $myApplications = $this->appModel->getUserApplications($_SESSION['user_id'], $start, self::ROWS_PER_PAGE, $selectedCompany);
        $totalRows = $this->appModel->getApplicationCount($_SESSION['user_id'], $selectedCompany);
        
        $companies = $this->appModel->getAppliedCompanies($_SESSION['user_id']);

        $pages = ceil($totalRows / self::ROWS_PER_PAGE);

        include '../view/my-applications-view.php';
        exit();
    }
}

$controller = new MyApplicationsController($dbConn);
$controller->handleRequest();
?>