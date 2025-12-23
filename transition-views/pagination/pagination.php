<?php
    if(!isset($_GET['page-nr'])) {
        $page = 1;
    } else {
        $page = (int)$_GET['page-nr'];
    }
?>

<div class="table-wrapper">
    <div class="info">
        Showing <?php echo htmlspecialchars($page) ?> of <?php echo htmlspecialchars($pages) ?> Pages
    </div>

    <div class="pagination">
        <a href="?page-nr=1">First</a>

        <?php if($page > 1): ?>
            <a href="?page-nr=<?php echo $page - 1 ?>">Previous</a>
        <?php else: ?>
            <a class="disabled">Previous</a>
        <?php endif; ?>

        <div class="page-nums">
            <?php for($counter = 1; $counter <= $pages; $counter++): ?>
                <a href="?page-nr=<?php echo $counter ?>" 
                   class="<?php echo ($counter == $page) ? 'active' : ''; ?>">
                   <?php echo $counter ?>
                </a>
            <?php endfor; ?>
        </div>

        <?php if($page < $pages): ?>
            <a href="?page-nr=<?php echo $page + 1 ?>">Next</a>
        <?php else: ?>
            <a class="disabled">Next</a>
        <?php endif; ?>

        <a href="?page-nr=<?php echo htmlspecialchars($pages)?>">Last</a>
    </div>
</div>