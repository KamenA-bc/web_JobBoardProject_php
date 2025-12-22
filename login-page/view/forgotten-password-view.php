<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../view/forgotten-password-style.css">
</head>
<body>

    <div class="forgot-container">
        <div class="header-section">
            <h2>Reset Password</h2>
            <p>Enter your username and your new password directly.</p>
        </div>

        <form method="post" action="../controller/forgotten-password-controller.php">
            
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username_or_email" placeholder="Enter your username or email" required>

            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>

            <input type="submit" name="submit" value="Update Password">
        </form>

        <?php if (isset($errorMessage)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <p style="color:green;"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>

        <div class="footer-links">
            <a href="../../login-page/controller/login-controller.php">← Back to Login</a>
        </div>
    </div>

</body>
</html>