<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In Page</title>
    <link rel="stylesheet" href="login-style.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <img src="../images/business.png" alt="Job Board Image">
        </div>

        <div class="right-side">
            <h1>Log In</h1>
            <form method="post" action="login-controller.php">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username/email" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>

                <input type="submit" name="submit" value="Log In">
            </form>
            <a href="../register-page/register-view.php" class="register-link">Create an account</a>
                <?php if (isset($errorMessage)): ?>
                    <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
        </div>
    </div>
</body>
</html>