<?php 
include '../../transition-views/menu/menu.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Jobs</title>
    <link rel="stylesheet" href="../view/CSS/job-browse-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
    <link rel="stylesheet" href="../../transition-views/pagination/pagination-style.css"> </head>
<body>

<div class="container">
    <div class="header-section">
        <h2>Find Your Next Opportunity</h2>
        <p>Browse the latest openings from top companies</p>
    </div>

    <?php if (!empty($jobs)): ?>
        <div class="job-list">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-info">
                        <h3><?php echo htmlspecialchars($job['title_name']); ?></h3>
                        <span class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></span>
                        
                        <div class="job-tags">
                            <span class="tag seniority"><?php echo htmlspecialchars($job['seniority_name']); ?></span>
                            <span class="tag location">📍 <?php echo htmlspecialchars($job['location']); ?></span>
                        </div>
                    </div>
                    
                    <div class="job-action">
                        <a href="#" class="apply-btn">Apply Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php include '../../transition-views/pagination/pagination.php'; ?>

    <?php else: ?>
        <div class="no-jobs">
            <p>No active job postings found. Check back later!</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
