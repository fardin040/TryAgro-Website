<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'About | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section class="page-intro container section">
    <p class="eyebrow">About us</p>
    <h1>Helping farms thrive with practical innovation</h1>
    <p>TryAgro partners with growers through every stage of the season with products, education, and field support.</p>
</section>

<section class="container section card-grid">
    <article class="card">
        <h2>Our mission</h2>
        <p>Deliver dependable solutions that improve productivity while preserving long-term soil and ecosystem health.</p>
    </article>
    <article class="card">
        <h2>Our approach</h2>
        <p>Combine local agricultural knowledge with modern best practices and transparent guidance.</p>
    </article>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
