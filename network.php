<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = ucfirst(str_replace('-', ' ', 'network')) . ' | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2><?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', 'network')), ENT_QUOTES, 'UTF-8'); ?></h2>
    <p>This is the network.php page.</p>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
