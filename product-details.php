<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Product Details | ' . SITE_NAME;

$productId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$product = null;

if ($productId !== false && $productId !== null) {
    $product = db_fetch_one(
        'SELECT p.id, p.name, p.image, p.description, p.details, c.name AS category_name
         FROM products p
         INNER JOIN categories c ON c.id = p.category_id
         WHERE p.id = :id
         LIMIT 1',
        [':id' => (int) $productId]
    );
}

require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Product Details</h2>
    <?php if ($product !== null) : ?>
        <article class="card">
            <h3><?php echo sanitize_output($product['name']); ?></h3>
            <p><strong>Category:</strong> <?php echo sanitize_output($product['category_name']); ?></p>
            <?php if (!empty($product['image'])) : ?>
                <p><strong>Image path:</strong> <?php echo sanitize_output($product['image']); ?></p>
            <?php endif; ?>
            <p><strong>Description:</strong> <?php echo nl2br(sanitize_output($product['description'])); ?></p>
            <p><strong>Details:</strong><br><?php echo nl2br(sanitize_output($product['details'])); ?></p>
        </article>
    <?php else : ?>
        <p class="alert-error">Invalid product ID or product not found.</p>
    <?php endif; ?>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
