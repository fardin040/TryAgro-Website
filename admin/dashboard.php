<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_admin_auth();

$cards = [
    'Products' => (int) (db_fetch_one('SELECT COUNT(*) AS total FROM products')['total'] ?? 0),
    'Categories' => (int) (db_fetch_one('SELECT COUNT(*) AS total FROM categories')['total'] ?? 0),
    'Dealers' => (int) (db_fetch_one('SELECT COUNT(*) AS total FROM dealers')['total'] ?? 0),
    'Messages' => (int) (db_fetch_one('SELECT COUNT(*) AS total FROM messages')['total'] ?? 0),
];

$pageTitle = 'Admin Dashboard | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo sanitize_output($_SESSION['admin_username'] ?? 'Admin'); ?>.</p>
    <p>
        <a href="/admin/categories.php">Categories</a> |
        <a href="/admin/products.php">Products</a> |
        <a href="/admin/dealers.php">Dealers</a> |
        <a href="/admin/videos.php">Videos</a> |
        <a href="/admin/pages.php">Pages</a> |
        <a href="/admin/messages.php">Messages</a> |
        <a href="/admin/logout.php">Logout</a>
    </p>

    <div style="display:grid;grid-template-columns:repeat(2,minmax(180px,1fr));gap:16px;max-width:700px;">
        <?php foreach ($cards as $label => $value): ?>
            <article style="border:1px solid #ddd;padding:16px;border-radius:8px;">
                <h3><?php echo sanitize_output($label); ?></h3>
                <p style="font-size:2rem;margin:0;"><?php echo sanitize_output((string) $value); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
