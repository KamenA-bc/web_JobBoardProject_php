<?php include '../../transition-views/menu/menu.php'; ?>
<?php
if (!isset($companies)) {
    header("Location: ../controller/company-edit-controller.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company List</title>
    <link rel="stylesheet" href="../view/CSS/company-edit-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
    <link rel="stylesheet" href="../../transition-views/pagination/pagination-style.css">
</head>
<body>

    <h2>All Companies</h2>

    <?php if (!empty($companies)): ?>
        <table>
            <tr>
                <th>Company Name</th>
                <th>Company URL</th>
                <th>Edit the company</th>
            </tr>

            <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?php echo htmlspecialchars($company['name']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($company['site_url']); ?>"><?php echo htmlspecialchars($company['site_url']); ?></a></td>
                    <td><a href="../controller/company-edit-controller.php?action=edit&id=<?php echo htmlspecialchars($company['id']); ?>" class="edit-btn">Edit</a></td>
                </tr>
            <?php endforeach; ?>

        </table>

        <?php include '../../transition-views/pagination/pagination.php'; ?>
        <?php else: ?>

        <p>No companies found.</p>

    <?php endif; ?>
</body>
</html>