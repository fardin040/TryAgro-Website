<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Product Details | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section class="container section">
    <p class="eyebrow">Featured product</p>
    <h1>YieldMax Nutrient Blend</h1>
    <div class="card-grid">
        <article class="card">
            <h2>Benefits</h2>
            <ul>
                <li>Supports stronger root development.</li>
                <li>Improves nutrient uptake during rapid growth.</li>
                <li>Helps crops recover after stress events.</li>
            </ul>
        </article>
        <article class="card">
            <h2>Usage</h2>
            <p>Apply at recommended growth stages. Contact your local dealer for dosage by crop type and field condition.</p>
            <a class="btn" href="/contact.php">Request guidance</a>
        </article>
    </div>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
