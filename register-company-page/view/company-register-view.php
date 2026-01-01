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

    <?php if (isset($errorMessage)): ?>
        <p style="color:red; margin-bottom: 15px;"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <p style="color:green; margin-bottom: 15px;"><?php echo htmlspecialchars($successMessage); ?></p>
    <?php endif; ?>

    <form method="post" action="../controller/company-register-controller.php" class="company-form">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        
        <label>Company Name</label>
        <input type="text" name="companyName" placeholder="Enter company name" required>

        <label>Company URL</label>
        <input type="url" name="companyURL" placeholder="Enter company website URL" required>

        <input type="submit" name="submit_register" value="Register">
    </form>

    <hr class="divider">

    <h2>Your Registered Companies</h2>
    
    <?php if (isset($companies) && count($companies) > 0): ?>
        <table class="company-list">
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Website</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($company['name']); ?></strong>
                        </td>
                        <td>
                            <a href="<?php echo htmlspecialchars($company['site_url']); ?>" target="_blank" style="color: #1976D2; text-decoration: none;">
                                <?php echo htmlspecialchars($company['site_url']); ?>
                            </a>
                        </td>
                        <td>
                            <form method="POST" action="../controller/company-register-controller.php" style="display:inline-block;">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <input type="hidden" name="delete_company_id" value="<?php echo $company['id']; ?>">
                                
                                <button type="submit" 
                                        class="btn-delete" 
                                        onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($company['name']); ?>? This action cannot be undone.');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-state">You have not registered any companies yet.</p>
    <?php endif; ?>
</div>

</body>
</html>