<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In Page</title>
    <link rel="stylesheet" href="../view/login-style.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <img src="../../images/business.png" alt="Job Board Image">
        </div>

        <div class="right-side">
            <h1>Log In</h1>
            <form method="post" action="../controller/login-controller.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username/email" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>

                <input type="submit" name="submit" value="Log In">
            </form>

            <div class="links-container">
                <a href="../../register-page/controller/register-controller.php" class="register-link">Create an account</a>
                <a href="../controller/forgotten-password-controller.php" class="forgot-link">Forgot Password?</a>
            </div>

            <?php if (isset($errorMessage)): ?>
                <p style="color:red; margin-top: 15px;"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>