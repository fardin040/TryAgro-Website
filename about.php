<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Fetch dynamic content from the database
$about = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'about_text']);
$mission = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'about_mission']);
$vision = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'about_vision']);

$pageTitle = 'About | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>

<section class="page-intro container section">
    <p class="eyebrow">About us</p>
    <h1>Helping farms thrive with practical innovation</h1>
    <p><?php echo nl2br(sanitize_output($about['content'] ?? 'TryAgro partners with growers through every stage of the season.')); ?></p>
</section>

<section class="container section card-grid">
    <article class="card">
        <h2>Our mission</h2>
        <p><?php echo nl2br(sanitize_output($mission['content'] ?? 'Deliver dependable solutions that improve productivity.')); ?></p>
    </article>
    <article class="card">
        <h2>Our vision</h2>
        <p><?php echo nl2br(sanitize_output($vision['content'] ?? 'Our vision details are being updated.')); ?></p>
    </article>
</section>

<?php require_once PROJECT_ROOT . '/footer.php'; ?>