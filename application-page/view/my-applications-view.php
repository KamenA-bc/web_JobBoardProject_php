<?php include '../../transition-views/menu/menu.php';
DEFINE('INTERVIEW_STATUS', 3);?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications</title>
    <link rel="stylesheet" href="../view/CSS/my-application-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
    <link rel="stylesheet" href="../../transition-views/pagination/pagination-style.css"> 
</head>
<body>

<div class="container">
    <div class="header">
        <h2>My Applications</h2>
        <p>Track the status of your current job applications.</p>
    </div>

    <div class="filter-section">
        <strong>Filter by Company:</strong>
        <form method="GET" class="filter-form">
            <select name="company" class="filter-select" onchange="this.form.submit()">
                <option value="">All Companies</option>
                <?php foreach ($companies as $comp): ?>
                    <option value="<?php echo $comp['id']; ?>" 
                        <?php echo (isset($selectedCompany) && $selectedCompany == $comp['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($comp['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <?php if(isset($selectedCompany) && $selectedCompany != ''): ?>
                <a href="my-applications-controller.php" class="btn-reset">✖ Clear Filter</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!empty($myApplications)): ?>
        <div class="table-responsive">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Applied On</th>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myApplications as $app): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($app['applied_on'])); ?></td>
                            <td class="job-title"><?php echo htmlspecialchars($app['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($app['location']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($app['status_name']); ?>">
                                    <?php echo htmlspecialchars($app['status_name']); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    if ($app['status_id'] == INTERVIEW_STATUS) {
                                        $link = "../../transition-views/interview-questions/interview-form.php?app_id=" . $app['id'];
                                    } else {
                                        $link = "../../transition-views/no-details/no-details.php";
                                    }
                                ?>
                                <a href="<?php echo $link; ?>" class="view-btn">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php include '../../transition-views/pagination/pagination.php'; ?>
        
    <?php else: ?>
    
        <div class="empty-state">
            <?php if(isset($selectedCompany) && $selectedCompany != ''): ?>
                <h3>No applications found for this company.</h3>
                <a href="my-applications-controller.php" class="view-btn">Clear Filter</a>
            <?php else: ?>
                <h3>You haven't applied to any jobs yet.</h3>
                <a href="../../job-page/controller/job-browse-controller.php" class="cta-btn">Browse Jobs</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>