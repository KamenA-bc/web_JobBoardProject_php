<?php include '../../transition-views/menu/menu.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Positions</title>
    <link rel="stylesheet" href="../view/CSS/position-manager-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
</head>
<body>

<div class="container">
    
    <h2>Manage Job Positions</h2>
    <p class="description">Below is a list of all job positions posted by your companies. You can delete positions only if they have no pending active applications.</p>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if (isset($positions) && count($positions) > 0): ?>
        <table class="position-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Seniority</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($positions as $pos): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($pos['title_name']); ?></strong>
                        </td>
                        <td>
                            <span class="badge badge-company">
                                <?php echo htmlspecialchars($pos['company_name']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($pos['seniority_name']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($pos['location']); ?>
                        </td>
                        <td>
                            <span class="badge badge-status">
                                <?php echo htmlspecialchars($pos['status_name']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="../controller/position-manager-controller.php">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="delete_position_id" value="<?php echo $pos['id']; ?>">
                                
                                <button type="submit" 
                                        class="btn-delete"
                                        onclick="return confirm('Delete position: <?php echo htmlspecialchars($pos['title_name']); ?>?\n\nNote: Deletion will fail if there are active applications.');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            You have not posted any job positions yet.
        </div>
    <?php endif; ?>

</div>

</body>
</html>

