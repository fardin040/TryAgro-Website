<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Logic from main: Securely fetch the product ID and data
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

// Dynamically set page title based on product name
$pageTitle = ($product ? $product['name'] : 'Product Not Found') . ' | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>

<section class="container section">
    <?php if ($product !== null) : ?>
        <div class="page-intro">
            <p class="eyebrow"><?php echo sanitize_output($product['category_name']); ?></p>
            <h1><?php echo sanitize_output($product['name']); ?></h1>
            <p><?php echo nl2br(sanitize_output($product['description'])); ?></p>
        </div>

        <div class="card-grid">
            <article class="card">
                <h2>Product Details</h2>
                <div class="prose">
                    <?php echo nl2br(sanitize_output($product['details'])); ?>
                </div>
            </article>

            <article class="card">
                <h2>Usage & Support</h2>
                <p>Apply at recommended growth stages. Contact your local dealer for dosage by crop type and field condition.</p>
                
                <?php if (!empty($product['image'])) : ?>
                    <p class="muted" style="font-size: 0.8rem; margin-top: 1rem;">
                        Reference Image: <?php echo sanitize_output($product['image']); ?>
                    </p>
                <?php endif; ?>

                <a class="btn" href="/contact.php" style="margin-top: 1rem; display: inline-block;">Request guidance</a>
            </article>
        </div>
        
    <?php else : ?>
        <div class="alert-error">
            <h2>Product Not Found</h2>
            <p>We couldn't find the product you're looking for. Please return to the <a href="/products.php">product catalog</a>.</p>
        </div>
    <?php endif; ?>
</section>

<?php require_once PROJECT_ROOT . '/footer.php'; ?>