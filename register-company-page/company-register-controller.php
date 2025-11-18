<?php
session_start(); // Start session at the top

include "../config.php";
include "company-register-model.php";

$companyRegisterModel = new companyRegisterModel($dbConn);

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
    
    $result = $companyRegisterModel->registerCompany(
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
