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
    <link rel="stylesheet" href="../../transition-views/pagination/pagination-style.css"> 
</head>
<body>

<div class="container">
    <div class="header-section">
        <h2>Find Your Next Opportunity</h2>
        <p>Browse the latest openings from top companies</p>
    </div>

    <div class="filter-section">
        <strong>Filter by:</strong>
        <form method="GET" class="filter-form">
            <select name="seniority" class="filter-select" onchange="this.form.submit()">
                <option value="">All Seniority Levels</option>
                <?php foreach ($seniorities as $sen): ?>
                    <option value="<?php echo $sen['id']; ?>" 
                        <?php echo (isset($selectedSeniority) && $selectedSeniority == $sen['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($sen['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <noscript><button type="submit" class="btn-filter">Filter</button></noscript>
            
            <?php if(isset($selectedSeniority) && $selectedSeniority != ''): ?>
                <a href="job-browse-controller.php" class="btn-reset">✖ Clear Filter</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert-success">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

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
                        <form action="../controller/job-browse-controller.php" method="POST">
                            <input type="hidden" name="position_id" value="<?php echo $job['id']; ?>">
                            <button type="submit" name="apply_now" class="apply-btn">Apply Now</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php 
        include '../../transition-views/pagination/pagination.php'; 
        ?>

    <?php else: ?>
        <div class="no-jobs">
            <p>No jobs found matching your criteria.</p>
            <a href="job-browse-controller.php" style="color: #1565C0; margin-top:10px; display:inline-block;">View all jobs</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>