<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Page</title>
    <link rel="stylesheet" href="../view/register-style.css">
</head>
<body>
    <div class="container register-page">
        <div class="left-side">
            <h1>Register</h1>
            <form method="post" action="../controller/register-controller.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required>
                
                <label>First Name</label>
                <input type="text" name="first_name" placeholder="Enter first name" reqired>
                
                <label>Last Name</label>
                <input type="text" name="last_name" placeholder="Enter last name" reqired>

                <label>E-mail</label>
                <input type="text" name="email" placeholder="Enter email" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>

                <label>Repeat password</label>
                <input type="password" name="repeatPassword" placeholder="Repeat password" required>

                <input type="submit" name="submit" value="Register">
            </form>
            <a href="../../login-page/controller/login-controller.php" class="register-link">Log In</a>
                <?php if (isset($errorMessage)): ?>
                <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
        </div>
        <div class="right-side">
            <img src="../../images/business_lady.png" alt="Job Board Image">
        </div>
    </div>
</body>
</html>