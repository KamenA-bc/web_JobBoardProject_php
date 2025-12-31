<?php 
// View checks handled by controller
?>

<?php include '../../transition-views/menu/menu.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a New Job</title>
    <link rel="stylesheet" href="../view/CSS/post_job-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
</head>
<body>

<div class="form-container">
    <h2>Post a New Job</h2>
    <p class="subtitle">Find the perfect candidate for your company</p>

    <?php if (isset($errorMessage)): ?>
        <div class="message error">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <div class="message success">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    <form action="../controller/post_job-controller.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

        <div class="form-group">
            <label for="company_id">Company</label>
            <select name="company_id" id="company_id" required>
                <option value="" disabled selected>Select your company...</option>
                <?php foreach ($myCompanies as $company): ?>
                    <option value="<?php echo $company['id']; ?>">
                        <?php echo htmlspecialchars($company['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row">
            <div class="form-group half">
                <label for="title_id">Job Title</label>
                <select name="title_id" id="title_id" required>
                    <option value="" disabled selected>Select role...</option>
                    <?php foreach ($titles as $title): ?>
                        <option value="<?php echo $title['id']; ?>">
                            <?php echo htmlspecialchars($title['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group half">
                <label for="seniority_id">Seniority Level</label>
                <select name="seniority_id" id="seniority_id" required>
                    <option value="" disabled selected>Select level...</option>
                    <?php foreach ($seniorities as $level): ?>
                        <option value="<?php echo $level['id']; ?>">
                            <?php echo htmlspecialchars($level['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" placeholder="e.g. Remote, Sofia, New York" required>
        </div>

        <button type="submit" name="post_job" class="submit-btn">Publish Job</button>
    </form>
</div>

</body>
</html>