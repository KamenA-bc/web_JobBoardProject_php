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
    <link rel="stylesheet" href="company-edit-style.css">
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
                    <td><?php echo htmlspecialchars($company['name']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($company['site_url']); ?>"><?php echo htmlspecialchars($company['site_url']); ?></a></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <div class="table-wrapper">
        <div class="info">
            <?php
                if(!isset($_GET['page-nr']))
                {
                    $page = 1;
                }
                else
                {
                    $page = $_GET['page-nr'];
                }
            ?>
            Showing <?php echo htmlspecialchars($page) ?> of <?php echo htmlspecialchars($pages) ?> Pages
        </div>

        <div class="pagination">
            <a href="?page-nr=1">First</a>
            <?php
                if(isset($_GET['page-nr']) && $_GET['page-nr'] > 1)
                {
            ?>
                <a href="?page-nr=<?php echo htmlspecialchars($_GET['page-nr'] - 1) ?>">Previous</a>
            <?php
                } else
                {
            ?>
            <a href="">Previous</a>
            <?php
                }
            ?>
            <div class="page-nums">
                <?php
                    for($counter = 1; $counter <= $pages; $counter++)
                    {
                ?>
                <a href="?page-nr=<?php echo htmlspecialchars($counter)?>"><?php echo htmlspecialchars($counter)?></a>
                <?php
                    }
                ?>
            </div>
            <?php
            if(!isset($_GET['page-nr'])){
            ?>
                <a href="?page-nr=2">Next</a>
            <?php
            }else{
                if($_GET['page-nr'] >= $pages){
            ?>
                    <a href="">Next</a>
            <?php
                }else{
            ?>
                    <a href="?page-nr=<?php echo $_GET['page-nr'] + 1 ?>">Next</a>
            <?php
                }
            }
            ?>
            <a href="?page-nr= <?php echo htmlspecialchars($pages)?>">Last</a>
        </div>
    </div>
    <?php else: ?>

        <p>No companies found.</p>

    <?php endif; ?>
</body>
</html>
