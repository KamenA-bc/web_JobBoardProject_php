<?php 
if (file_exists('menu/menu.php')) {
    include 'menu/menu.php'; 
} elseif (file_exists('../transition-views/menu/menu.php')) {
    include '../transition-views/menu/menu.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Details</title>
    <link rel="stylesheet" href="menu/menu-style.css">
    <style>
        body { background-color: #F4F6F8; font-family: 'Segoe UI', Arial, sans-serif; }
        .info-card {
            max-width: 600px; margin: 80px auto; background: white;
            padding: 50px; border-radius: 8px; text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        h2 { color: #555; margin-bottom: 15px; }
        p { color: #777; font-size: 1.1rem; margin-bottom: 30px; }
        .btn-back {
            display: inline-block; padding: 10px 25px; background-color: #1565C0;
            color: white; text-decoration: none; border-radius: 5px; font-weight: 600;
        }
        .btn-back:hover { background-color: #0D47A1; }
        .icon { font-size: 40px; margin-bottom: 20px; display: block; }
    </style>
</head>
<body>

<div class="info-card">
    <span class="icon">ℹ️</span>
    <h2>No Additional Details</h2>
    <p>There are no additional forms or actions required for this application stage at the moment.</p>
    
    <a href="../../application-page/controller/my-applications-controller.php" class="btn-back">Back to Applications</a>
</div>

</body>
</html>