<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Network | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section class="page-intro container section">
    <p class="eyebrow">Our network</p>
    <h1>Find trusted partners near you</h1>
    <p>Our dealer and advisor network provides local support wherever you operate.</p>
</section>
<section class="container section card-grid">
    <article class="card">
        <h2>Regional dealers</h2>
        <p>Get product access, pricing, and inventory updates from nearby suppliers.</p>
    </article>
    <article class="card">
        <h2>Agronomy advisors</h2>
        <p>Receive recommendations tailored to crop cycle, soil type, and local conditions.</p>
    </article>
    <article class="card">
        <h2>Support center</h2>
        <p>Need help fast? Connect with our technical team for guidance and troubleshooting.</p>
    </article>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
