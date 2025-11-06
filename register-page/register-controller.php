  <?php
        include "../config.php";
        include "register-model.php";
        
    $registerModel = new registerModel($dbConn);
    
    if(isset($_POST["submit"]))
    {
        $username = $_POST["username"];
        $firstName = $_POST["first_name"];
        $lastName = $_POST["last_name"];
        $email= $_POST["email"];
        $password= $_POST["password"];
        $repeatPassword = $_POST["repeatPassword"];

        $registerModel->registerUser($username, $firstName, $lastName, $email, $password, $repeatPassword);
    }


