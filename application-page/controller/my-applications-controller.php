<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../config.php";
include "../model/application-model.php";

class MyApplicationsController
{
    private $appModel;

    public function __construct($dbConn)
    {
        $this->appModel = new ApplicationModel($dbConn);
    }

    public function handleRequest()
    {
        
        if (empty($_SESSION['user_id'])) 
        {
            header("Location: ../../login-page/view/login-view.php");
            exit();
        }

       
        $myApplications = $this->appModel->getUserApplications($_SESSION['user_id']);

        
        include '../view/my-applications-view.php';
    }
}

$controller = new MyApplicationsController($dbConn);
$controller->handleRequest();
?>
