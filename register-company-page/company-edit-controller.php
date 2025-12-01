<?php
session_start(); // Start session at the top
include "../config.php";
include "company-model.php";

class companyEditController
{
        private $companyRegisterModel;
    private $errorMessage;
    private $successMessage;
    
    public function __construct($dbConn) 
    {
        $this->companyRegisterModel = new companyModel($dbConn);
    }
    
    public function showCompanies()
    {
        $companies = $this->companyRegisterModel->selectAll();
    }
    
    private function loadView() 
    {
        $errorMessage = $this->errorMessage;
        $successMessage = $this->successMessage;
        include 'company-register-view.php';
    }
}
$controller = new companyEditController($dbConn);
$controller->handleRequest();

