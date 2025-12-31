<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../config.php";
include "../model/job-model.php";

class JobBrowseController
{
    private $jobModel;
    const ROWS_PER_PAGE = 5;

    public function __construct($dbConn)
    {
        $this->jobModel = new JobModel($dbConn);
    }

    public function handleRequest()
    {
        $this->listJobs();
    }

    private function listJobs()
    {
        $page = isset($_GET['page-nr']) ? (int)$_GET['page-nr'] : 1;
        $start = ($page - 1) * self::ROWS_PER_PAGE;

        $jobs = $this->jobModel->getActiveJobs($start, self::ROWS_PER_PAGE);
        $totalRows = $this->jobModel->getActiveJobCount();
        
        $pages = ceil($totalRows / self::ROWS_PER_PAGE);

        include '../view/job-browse-view.php';
        exit();
    }
}

$controller = new JobBrowseController($dbConn);
$controller->handleRequest();
?>