<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="/job_board_project/transition-views/menu/menu-style.css">

<nav>
    <div class="nav-links">
        <a href="/job_board_project/main-page/main-page.php">Home</a>

        <div class="dropdown">
            <span class="dropdown-btn">Companies ▼</span>
            <div class="dropdown-content">
                <a href="/job_board_project/register-company-page/controller/company-register-controller.php">Register Company</a>
                <a href="/job_board_project/register-company-page/controller/company-edit-controller.php">List / Edit Companies</a>
            </div>
        </div>

        <div class="dropdown">
            <span class="dropdown-btn">Jobs ▼</span>
            <div class="dropdown-content">
                <a href="/job_board_project/job-page/controller/job-browse-controller.php">Browse Jobs</a>
                
                <a href="/job_board_project/job-page/controller/post_job-controller.php">Post a Job</a>
                <a href="/job_board_project/job-page/controller/position-manager-controller.php">Manage Positions</a>
                
                <a href="/job_board_project/application-page/controller/manage-applications-controller.php">Screen Applications</a>
                
                <a href="/job_board_project/application-page/controller/my-applications-controller.php">My Applications</a>
            </div>
        </div>

        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <a href="/job_board_project/admin/controller/admin-controller.php" style="font-weight: bold; color: #FFD700;">Admin Panel</a>
        <?php endif; ?>

    </div>

    <div class="user-menu">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            
            <a href="/job_board_project/transition-views/menu/logout-controller.php" class="btn-logout">Logout</a>
            
        <?php else: ?>
            <span>Guest</span>
            <a href="/job_board_project/login-page/controller/login-controller.php" class="btn-login">Login</a>
        <?php endif; ?>
    </div>
</nav>