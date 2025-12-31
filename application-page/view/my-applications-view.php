<?php include '../../transition-views/menu/menu.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications</title>
    <link rel="stylesheet" href="../view/CSS/my-application-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2>My Applications</h2>
        <p>Track the status of your current job applications.</p>
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
                                <a href="#" class="view-btn">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>You haven't applied to any jobs yet.</h3>
            <a href="../../job-page/controller/job-browse-controller.php" class="cta-btn">Browse Jobs</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>