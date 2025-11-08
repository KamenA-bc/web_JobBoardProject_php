  <?php
        include "../config.php";
        include "register-model.php";
        
    $registerModel = new registerModel($dbConn);
    
    if(isset($_POST["submit"]))
    {
        $result = $registerModel->registerUser(
            $_POST['username'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['repeatPassword']
        );

        if ($result['success']) 
        {
            $successMessage = $result['message'];
            include 'register-view.php';
        } 
        else 
        {
            $errorMessage = $result['error'];
            include 'register-view.php';
        }
    }


