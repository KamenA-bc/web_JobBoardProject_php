<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

include "../../config.php";
include "../model/job-model.php";
include "../../application-page/model/application-model.php"; 

class JobBrowseController
{
    private $jobModel;
    private $appModel;
    const ROWS_PER_PAGE = 10;

    public function __construct($dbConn)
    {
        $this->jobModel = new JobModel($dbConn);
        $this->appModel = new ApplicationModel($dbConn);
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_now'])) 
        {
            $this->processApplication();
        } 

        else 
        {
            $this->listJobs();
        }
    }


    private function processApplication()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../login-page/view/login-view.php");
            exit();
        }

        $userId = $_SESSION['user_id'];
        $positionId = $_POST['position_id'] ?? null;

        if ($positionId) {
            
            if ($this->appModel->checkIfApplied($userId, $positionId)) 
            {
                header("Location: ../../transition-views/already-applied/already-applied-page.php");
                exit();
            }
            $result = $this->appModel->applyForJob($userId, $positionId);

            if ($result['success']) 
            {
                header("Location: ../../transition-views/success-page/success-page.php");
                exit();
            } else {
                echo htmlspecialchars("error");
                $this->listJobs(); 
            }
        }
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