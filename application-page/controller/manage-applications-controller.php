<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../config.php";
include "../../application-page/model/application-model.php"; 

class ManageApplicationsController
{
    private $appModel;

    public function __construct($dbConn)
    {
        $this->appModel = new ApplicationModel($dbConn);
        
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function handleRequest()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: ../../login-page/view/login-view.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $this->processUpdate();
        } else {
            $this->showDashboard();
        }
    }

    private function processUpdate()
    {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $msg = "Invalid CSRF token.";
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=" . urlencode($msg) . "&type=error");
            exit();
        }

        $appId = $_POST['app_id'];
        $newStatusId = $_POST['status_id'];

        if ($this->appModel->updateStatus($appId, $newStatusId)) {
            $msg = "Status updated successfully.";
            $type = "success";
        } else {
            $msg = "Update failed: You cannot move an application backwards in the process.";
            $type = "error";
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=" . urlencode($msg) . "&type=" . $type);
        exit();
    }

    private function showDashboard()
    {
        $ownerId = $_SESSION['user_id'];
        $applications = $this->appModel->getOwnerApplications($ownerId);
        $statuses = $this->appModel->getAllStatuses();
        include '../view/manage-application-view.php';
    }
}

$controller = new ManageApplicationsController($dbConn);
$controller->handleRequest();
?>