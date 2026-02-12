<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Logic from main: Handle Category Filtering
$categories = db_fetch_all('SELECT id, name FROM categories ORDER BY name ASC');
$selectedCategoryId = null;

if (isset($_GET['category_id'])) {
    $candidate = filter_var($_GET['category_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($candidate !== false) {
        $selectedCategoryId = (int) $candidate;
    }
}

// Logic from main: Fetch Products (All or Filtered)
$query = 'SELECT p.id, p.name, p.description, p.image, c.name AS category_name 
          FROM products p 
          INNER JOIN categories c ON c.id = p.category_id';
$params = [];

if ($selectedCategoryId !== null) {
    $query .= ' WHERE p.category_id = :category_id';
    $params[':category_id'] = $selectedCategoryId;
}

$query .= ' ORDER BY p.id DESC';
$products = db_fetch_all($query, $params);

$pageTitle = 'Products | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>

<section class="page-intro container section">
    <p class="eyebrow">Product catalog</p>
    <h1>Inputs built for efficiency and resilience</h1>
    <p>Our catalog supports seedling health, crop nutrition, and in-season crop protection.</p>
</section>

<section class="container section">
    <h2 class="muted" style="font-size: 0.9rem; text-transform: uppercase; margin-bottom: 1rem;">Filter by Category</h2>
    <div class="grid grid-3">
        <a class="btn <?php echo $selectedCategoryId === null ? '' : 'btn-secondary'; ?>" href="/products.php" style="text-align:center; text-decoration: none;">
            All Categories
        </a>
        <?php foreach ($categories as $category) : ?>
            <a class="btn <?php echo $selectedCategoryId === (int)$category['id'] ? '' : 'btn-secondary'; ?>" 
               href="/products.php?category_id=<?php echo (int) $category['id']; ?>" 
               style="text-align:center; text-decoration: none;">
                <?php echo sanitize_output($category['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="container section">
    <div class="card-grid">
        <?php foreach ($products as $product) : ?>
            <article class="card">
                <p class="eyebrow" style="margin-bottom: 0.5rem;"><?php echo sanitize_output($product['category_name']); ?></p>
                <h2><?php echo sanitize_output($product['name']); ?></h2>
                <p><?php echo sanitize_output($product['description']); ?></p>
                <a class="btn btn-secondary" href="/product-details.php?id=<?php echo (int) $product['id']; ?>" style="margin-top: auto;">
                    View details
                </a>
            </article>
        <?php endforeach; ?>

        <?php if ($products === []) : ?>
            <p class="muted">No products found for this category.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once PROJECT_ROOT . '/footer.php'; ?>