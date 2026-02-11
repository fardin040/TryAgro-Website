<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Videos | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section class="page-intro container section">
    <p class="eyebrow">Learning hub</p>
    <h1>Watch practical tips from the field</h1>
    <p>Explore short guides on nutrient timing, soil care, and crop protection best practices.</p>
</section>
<section class="container section card-grid">
    <article class="card">
        <h2>Soil readiness basics</h2>
        <p>Understand the pre-season checks that set up stronger early growth.</p>
    </article>
    <article class="card">
        <h2>Mid-season nutrition</h2>
        <p>How to maintain healthy crop development through key growth windows.</p>
    </article>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
