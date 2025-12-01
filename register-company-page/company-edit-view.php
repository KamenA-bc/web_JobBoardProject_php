<?php
if (!isset($companies)) {
    header("Location: company-edit-controller.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company List</title>
</head>
<body>

<h2>All Companies</h2>

<?php if (!empty($companies)): ?>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>Company Name</th>
            <th>Company URL</th>
        </tr>

        <?php foreach ($companies as $company): ?>
            <tr>
                <td><?= htmlspecialchars($company['name']); ?></td>
                <td><?= htmlspecialchars($company['site_url']); ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

<?php else: ?>

    <p>No companies found.</p>

<?php endif; ?>

</body>
</html>
