<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Products | ' . SITE_NAME;

$categories = db_fetch_all('SELECT id, name FROM categories ORDER BY name ASC');
$selectedCategoryId = null;

if (isset($_GET['category_id'])) {
    $candidate = filter_var($_GET['category_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($candidate !== false) {
        $selectedCategoryId = (int) $candidate;
    }
}

if ($selectedCategoryId !== null) {
    $products = db_fetch_all(
        'SELECT p.id, p.name, p.description, p.image, c.name AS category_name
         FROM products p
         INNER JOIN categories c ON c.id = p.category_id
         WHERE p.category_id = :category_id
         ORDER BY p.id DESC',
        [':category_id' => $selectedCategoryId]
    );
} else {
    $products = db_fetch_all(
        'SELECT p.id, p.name, p.description, p.image, c.name AS category_name
         FROM products p
         INNER JOIN categories c ON c.id = p.category_id
         ORDER BY p.id DESC'
    );
}

require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Categories</h2>
    <div class="grid grid-3">
        <a class="card" href="/products.php">All Categories</a>
        <?php foreach ($categories as $category) : ?>
            <a class="card" href="/products.php?category_id=<?php echo (int) $category['id']; ?>">
                <?php echo sanitize_output($category['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section>
    <h2>Product Listing</h2>
    <?php if ($selectedCategoryId !== null) : ?>
        <p class="muted">Filtered by category ID: <?php echo $selectedCategoryId; ?></p>
    <?php endif; ?>

    <div class="grid grid-2">
        <?php foreach ($products as $product) : ?>
            <article class="card">
                <h3><?php echo sanitize_output($product['name']); ?></h3>
                <p><strong>Category:</strong> <?php echo sanitize_output($product['category_name']); ?></p>
                <p><?php echo sanitize_output($product['description']); ?></p>
                <a href="/product-details.php?id=<?php echo (int) $product['id']; ?>">View details</a>
            </article>
        <?php endforeach; ?>
        <?php if ($products === []) : ?>
            <p class="muted">No products found for this filter.</p>
        <?php endif; ?>
    </div>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
