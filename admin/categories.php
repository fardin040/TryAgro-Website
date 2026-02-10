<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$pageTitle = 'Admin ' . ucfirst('categories') . ' | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Admin: <?php echo htmlspecialchars(ucfirst('categories'), ENT_QUOTES, 'UTF-8'); ?></h2>
    <p>This is the admin/categories.php page.</p>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
