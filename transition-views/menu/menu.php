<link rel="stylesheet" href="../transition-views/menu/menu-style.css">

<nav>
    <div class="nav-links">
        <a href="../../main-page/main-page.php">Home</a>

        <div class="dropdown">
            <span class="dropdown-btn">Companies ▼</span>
            <div class="dropdown-content">
                <a href="../register-company-page/controller/company-register-controller.php">Register Company</a>
                <a href="../register-company-page/controller/company-edit-controller.php">List / Edit Companies</a>
            </div>
        </div>

        <div class="dropdown">
            <span class="dropdown-btn">Jobs ▼</span>
            <div class="dropdown-content">
                <a href="#">Post a Job</a>
                <a href="#">Browse Jobs</a>
            </div>
        </div>
    </div>

    <div class="user-menu">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            
            <a href="../transition-views/menu/logout-controller.php" class="btn-logout">Logout</a>
            
        <?php else: ?>
            <span>Guest</span>
            <a href="../login-page/controller/login-controller.php" class="btn-login">Login</a>
        <?php endif; ?>
    </div>
</nav>