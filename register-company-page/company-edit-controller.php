<?php
include "../config.php";
include "company-model.php";


class CompanyEditController
{
    private $companyModel;
    
    public function __construct($dbConn) 
    {
        $this->companyModel = new CompanyModel($dbConn);
    }
    
    public function handleRequest()
    {
        $start = 0;
        if(isset($_GET['page-nr']))
        {
            
            $page = $_GET['page-nr'] - 1;
            
            $start = $page * ROWS_PER_PAGE;
        }
        
        $this->loadCompanies($start);
    }

    public function loadCompanies($start)
    {
        $companies = $this->companyModel->selectWithLimit($start);
        
        $rows = $this->companyModel->selectAll();
        
        $number_of_rows = count($rows);
        
        $pages = ceil($number_of_rows / ROWS_PER_PAGE);
        
        include 'company-edit-view.php';
    }
}

$controller = new CompanyEditController($dbConn);
$controller->handleRequest();

