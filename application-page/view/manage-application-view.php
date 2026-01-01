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
        <p>Review and update status for candidates applying to your companies.</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div style="background:#e8f5e9; color:#2e7d32; padding:10px; margin-bottom:15px; border-radius:4px; text-align:center;">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($applications)): ?>
        <div class="table-responsive">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Candidate</th>
                        <th>Job Position</th>
                        <th>Current Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): 
                        if ($app['status_id'] == 5 || $app['status_id'] == 6) {
            continue; 
        }?>
                    
                        <tr>
                            <td><?php echo date('M d', strtotime($app['applied_on'])); ?></td>
                            <td style="font-weight:bold;"><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($app['job_title']); ?>
                                <br>
                                <small style="color:#666;"><?php echo htmlspecialchars($app['company_name']); ?></small>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($app['status_name']); ?>">
                                    <?php echo htmlspecialchars($app['status_name']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="app_id" value="<?php echo $app['app_id']; ?>">
                                    
                                    <select name="status_id">
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?php echo $status['id']; ?>" 
                                                <?php echo ($status['id'] == $app['status_id']) ? 'selected' : ''; ?>>
                                                <?php echo ucfirst($status['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit" name="update_status" class="update-btn">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>No applications received yet.</h3>
        </div>
    <?php endif; ?>
</div>

</body>
</html>