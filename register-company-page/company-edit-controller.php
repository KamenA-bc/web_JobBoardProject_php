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
        $this->loadCompanies();
    }

    public function loadCompanies()
    {
        $companies = $this->companyModel->selectAll();

        include 'company-edit-view.php';
    }
}

$controller = new CompanyEditController($dbConn);
$controller->handleRequest();

