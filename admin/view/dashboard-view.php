<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../view/CSS/admin-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
</head>
<body>

<?php include '../../transition-views/menu/menu.php'; ?>

<div class="admin-container">
    <div class="admin-header">
        <h2>Admin Dashboard</h2>
        <p>Overview of system performance and activity.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total_users']; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total_active_jobs']; ?></div>
            <div class="stat-label">Active Jobs</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total_companies']; ?></div>
            <div class="stat-label">Registered Companies</div>
        </div>
    </div>

    <div class="content-grid">
        
        <div class="panel">
            <div class="panel-header">
                <h3>Newest Users</h3>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentUsers)): ?>
                        <?php foreach ($recentUsers as $user): ?>
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h3>System Audit Logs</h3>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action / Entity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($auditLogs)): ?>
                        <?php foreach ($auditLogs as $log): ?>
                            <tr>
                                <td class="timestamp">
                                    <?php echo date('M d, H:i', strtotime($log['created_at'])); ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($log['username'] ?? 'System'); ?></strong>
                                </td>
                                <td>
                                    Modified 
                                    <span class="entity-badge"><?php echo htmlspecialchars($log['entity']); ?></span>
                                    <small>(ID: <?php echo $log['entity_id']; ?>)</small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align:center; color:#999;">No logs available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>