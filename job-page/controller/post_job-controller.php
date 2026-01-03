<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../config.php";
include "../model/job-model.php";
// Ensure this path matches your folder structure exactly
include "../../register-company-page/model/company-model.php";

class JobPostController
{
    private $jobModel;
    private $companyModel;

    public function __construct($dbConn)
    {
        $this->jobModel = new JobModel($dbConn);
        $this->companyModel = new CompanyModel($dbConn);

        if (empty($_SESSION['csrf_token'])) 
        {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_job'])) 
        {
            $this->processJobPost();
        } 
        else 
        {
            $this->showForm();
        }
    }

    private function showForm($successMessage = null, $errorMessage = null)
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../login-page/view/login-view.php");
            exit();
        }

        $titles = $this->jobModel->getAllTitles();
        $seniorities = $this->jobModel->getAllSeniorities();
        
        $myCompanies = $this->companyModel->getCompaniesByOwner($_SESSION['user_id']);

        if (empty($myCompanies)) {
            $errorMessage = "You must register a company before you can post a job.";
        }

        include '../view/post_job-view.php';
        exit();
    }

    private function processJobPost()
    {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->showForm(null, "Security Token Invalid. Please reload.");
            return;
        }

        $companyId = $_POST['company_id'];
        $titleId = $_POST['title_id'];
        $seniorityId = $_POST['seniority_id'];
        $location = $_POST['location'];

        if (empty($companyId) || empty($titleId) || empty($seniorityId) || empty($location)) {
            $this->showForm(null, "Please fill in all required fields.");
            return;
        }


        $result = $this->jobModel->createJob(
            $companyId,
            $titleId,
            $location,
            $seniorityId,

        );

        if ($result['success']) {
            $_POST = []; 
            $this->showForm("Job successfully posted!", null);
        } else {
            $this->showForm(null, $result['error']);
        }
    }
}

$controller = new JobPostController($dbConn);
$controller->handleRequest();