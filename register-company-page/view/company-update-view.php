<?php
if (!isset($companyData)) {
    header("Location: ../controller/company-edit-controller.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Company</title>
    <link rel="stylesheet" href="../view/CSS/company-update-style.css">
</head>
<body>
    <div class="edit-container">
        <h1>Edit Company</h1>
        
        <form method="post" action="../controller/company-edit-controller.php">
            
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($companyData['id']); ?>">
            
            <label>Company Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($companyData['name']); ?>" required>
            
            <label>Website URL</label>
            <input type="url" name="site_url" value="<?php echo htmlspecialchars($companyData['site_url']); ?>" required>
            
            <input type="submit" name="update_company" value="Save Changes">
        </form>

        <div style="margin-top: 15px; text-align: center;">
            <?php if (isset($errorMessage)): ?>
                <p style="color: #D32F2F; font-weight: bold; background: #FFEBEE; padding: 10px; border-radius: 5px;">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </p>
            <?php endif; ?>

            <?php if (isset($successMessage)): ?>
                <p style="color: #388E3C; font-weight: bold; background: #E8F5E9; padding: 10px; border-radius: 5px;">
                    <?php echo htmlspecialchars($successMessage); ?>
                </p>
            <?php endif; ?>
        </div>
        <a href="../controller/company-edit-controller.php" class="back-link">← Back to List</a>
    </div>

</body>
</html>