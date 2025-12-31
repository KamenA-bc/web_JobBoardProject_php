<?php include '../../transition-views/menu/menu.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Register</title>
    <link rel="stylesheet" href="../view/CSS/company-register-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
</head>
<body>

<div class="container">
    <h2>Register Your Company</h2>
    <p>Fill in the details below to register your company on our job platform and start posting new job offers.</p>

    <form method="post" action="../controller/company-register-controller.php" class="company-form">
        
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        <label>Company Name</label>
        <input type="text" name="companyName" placeholder="Enter company name" required>

        <label>Company URL</label>
        <input type="url" name="companyURL" placeholder="Enter company website URL" required>

        <input type="submit" name="submit" value="Register">
    </form>
                <?php if (isset($errorMessage)): ?>
                <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>

                <?php if (isset($successMessage)): ?>
                <p style="color:green;"><?php echo htmlspecialchars($successMessage); ?></p>
                <?php endif; ?>
</div>

</body>
</html>
