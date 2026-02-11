<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Products | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section class="page-intro container section">
    <p class="eyebrow">Product catalog</p>
    <h1>Inputs built for efficiency and resilience</h1>
    <p>Our catalog supports seedling health, crop nutrition, and in-season crop protection.</p>
</section>
<section class="container section card-grid">
    <article class="card">
        <h2>Nutrition</h2>
        <p>Balanced formulations to support vigorous growth and stronger harvest quality.</p>
        <a class="btn btn-secondary" href="/product-details.php">View details</a>
    </article>
    <article class="card">
        <h2>Protection</h2>
        <p>Reliable crop-defense options designed for local pest and disease pressure.</p>
        <a class="btn btn-secondary" href="/product-details.php">View details</a>
    </article>
    <article class="card">
        <h2>Soil care</h2>
        <p>Soil conditioners and amendments that improve structure and nutrient availability.</p>
        <a class="btn btn-secondary" href="/product-details.php">View details</a>
    </article>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
