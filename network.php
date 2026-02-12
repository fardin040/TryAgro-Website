<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Logic from main: Fetch and group dealers
$dealers = db_fetch_all('SELECT region, district, name, address, phone FROM dealers ORDER BY region, district, name');
$grouped = [];

foreach ($dealers as $dealer) {
    $region = (string) $dealer['region'];
    $district = (string) $dealer['district'];
    $grouped[$region][$district][] = $dealer;
}

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

<section class="container section">
    <h2>Dealer Directory</h2>
    
    <?php if ($grouped === []) : ?>
        <p class="muted">No dealer entries available at this time.</p>
    <?php endif; ?>

    <div class="grid grid-2">
        <?php foreach ($grouped as $region => $districts) : ?>
            <article class="card">
                <h3 style="color: #065f46; border-bottom: 2px solid #ecfdf5; padding-bottom: 0.5rem;">
                    <?php echo sanitize_output($region); ?>
                </h3>
                
                <?php foreach ($districts as $district => $items) : ?>
                    <div style="margin-top: 1rem;">
                        <h4 style="margin-bottom: 0.5rem;"><?php echo sanitize_output($district); ?></h4>
                        <ul style="list-style: none; padding-left: 0;">
                            <?php foreach ($items as $item) : ?>
                                <li style="margin-bottom: 0.75rem; border-left: 3px solid #e5e7eb; padding-left: 0.75rem;">
                                    <strong><?php echo sanitize_output($item['name']); ?></strong><br>
                                    <?php if (!empty($item['address'])) : ?>
                                        <small class="muted"><?php echo sanitize_output($item['address']); ?></small><br>
                                    <?php endif; ?>
                                    <?php if (!empty($item['phone'])) : ?>
                                        <small><?php echo sanitize_output($item['phone']); ?></small>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once PROJECT_ROOT . '/footer.php'; ?>