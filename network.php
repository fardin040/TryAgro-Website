<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Dealer Network | ' . SITE_NAME;

$dealers = db_fetch_all('SELECT region, district, name, address, phone FROM dealers ORDER BY region, district, name');
$grouped = [];

foreach ($dealers as $dealer) {
    $region = (string) $dealer['region'];
    $district = (string) $dealer['district'];
    $grouped[$region][$district][] = $dealer;
}

require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Dealer Network</h2>
    <?php if ($grouped === []) : ?>
        <p class="muted">No dealer entries available.</p>
    <?php endif; ?>

    <?php foreach ($grouped as $region => $districts) : ?>
        <article class="card">
            <h3><?php echo sanitize_output($region); ?></h3>
            <?php foreach ($districts as $district => $items) : ?>
                <h4><?php echo sanitize_output($district); ?></h4>
                <ul>
                    <?php foreach ($items as $item) : ?>
                        <li>
                            <strong><?php echo sanitize_output($item['name']); ?></strong>
                            <?php if (!empty($item['address'])) : ?>
                                — <?php echo sanitize_output($item['address']); ?>
                            <?php endif; ?>
                            <?php if (!empty($item['phone'])) : ?>
                                (<?php echo sanitize_output($item['phone']); ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </article>
    <?php endforeach; ?>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
