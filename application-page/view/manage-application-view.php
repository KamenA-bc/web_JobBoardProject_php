<?php include '../../transition-views/menu/menu.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Applications</title>
    <link rel="stylesheet" href="../view/CSS/manage-application-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Applicant Screening</h2>
        <p class="description">Review candidates and update their application status.</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg-box msg-success">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($applications)): ?>
        <div class="table-responsive">
            <table class="app-table">
                <thead>
                    <tr>
                        <th width="15%">Date</th>
                        <th width="20%">Candidate</th>
                        <th width="25%">Position</th>
                        <th width="15%">Current Status</th>
                        <th width="25%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): 
                        if ($app['status_id'] == 5 || $app['status_id'] == 6) { continue; }
                    ?>
                        <tr>
                            <td>
                                <?php echo date('M d, Y', strtotime($app['applied_on'])); ?>
                            </td>
                            <td>
                                <span class="candidate-name"><?php echo htmlspecialchars($app['applicant_name']); ?></span>
                            </td>
                            <td>
                                <span class="job-title"><?php echo htmlspecialchars($app['job_title']); ?></span>
                                <span class="company-sub"><?php echo htmlspecialchars($app['company_name']); ?></span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($app['status_name']); ?>">
                                    <?php echo htmlspecialchars($app['status_name']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="app_id" value="<?php echo $app['app_id']; ?>">
                                    
                                    <select name="status_id">
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?php echo $status['id']; ?>" 
                                                <?php echo ($status['id'] == $app['status_id']) ? 'selected' : ''; ?>>
                                                <?php echo ucfirst($status['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>No active applications found.</h3>
            <p>Once candidates apply to your job postings, they will appear here.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>