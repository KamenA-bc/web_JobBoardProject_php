<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../config.php";
include "../model/admin-model.php";

DEFINE('ADMIN_DASHBOARD_PATH', '../view/dashboard-view.php');

class DashboardController
{
    private $adminModel;

    public function __construct($dbConn)
    {
        $this->adminModel = new AdminModel($dbConn);
    }

    public function handleRequest()
    {
        $this->checkAdminAccess();

        $stats = $this->adminModel->getDashboardStats();

        $recentUsers = $this->adminModel->getRecentUsers(5);

        $auditLogs = $this->adminModel->getAuditLogs(10);

        include ADMIN_DASHBOARD_PATH;
    }

    private function checkAdminAccess()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: ../../login-page/view/login-view.php");
            exit();
        }


        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
             header("Location: ../../transition-views/unauthorized-page.php");
             exit();
        }
    }
}

$controller = new DashboardController($dbConn);
$controller->handleRequest();

