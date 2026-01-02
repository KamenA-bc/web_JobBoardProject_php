<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}
include "../../config.php";
include "../model/job-model.php";

DEFINE('POSITION_VIEW_PATH', '../view/position-manager-view.php');
DEFINE('UNAUTHORIZED_PATH', '../../transition-views/unauthorized-page.php');

class PositionManagerController
{
    private $positionModel;

    public function __construct($dbConn)
    {
        $this->positionModel = new JobModel($dbConn);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function handleRequest()
    {
        if (empty($_SESSION["user_id"])) 
        {
            header("Location: ../../login-page/controller/login-controller.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $this->handlePost();
        } else {
            $this->loadView();
        }
    }

    private function handlePost()
    {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $errorMessage = "Invalid CSRF token.";
            $this->loadView($errorMessage);
            exit();
        }

        if (isset($_POST['delete_position_id'])) 
        {
            $positionId = $_POST['delete_position_id'];
            $userId = $_SESSION['user_id'];

            $result = $this->positionModel->deletePosition($positionId, $userId);

            if ($result['success']) 
            {
                $this->loadView(null, $result['message']);
            } 
            else 
            {
                if (isset($result['status']) && $result['status'] === 'unauthorized') {
                    header("Location: " . UNAUTHORIZED_PATH);
                    exit();
                }
                $this->loadView($result['error']);
            }
        }
    }

    private function loadView($errorMessage = null, $successMessage = null)
    {
        $positions = $this->positionModel->getPositionsByOwner($_SESSION['user_id']);
        include POSITION_VIEW_PATH;
    }
}

$controller = new PositionManagerController($dbConn);
$controller->handleRequest();