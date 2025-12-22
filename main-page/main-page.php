<!DOCTYPE html>
<?php
        include '../transition-views/menu/menu.php';
        if (session_status() === PHP_SESSION_NONE) 
        {
            session_start();
        }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        echo "Hi ".$_SESSION['username'] .  "<br>";
        echo "With id ".$_SESSION['user_id']. "<br>";
        echo "ROLE:".$_SESSION['role_id'];
        ?>
        <a href="../register-company-page/view/company-edit-view.php">click</a>
    </body>
</html>
