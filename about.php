<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'About | ' . SITE_NAME;

$about = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'about_text']);
$mission = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'about_mission']);
$vision = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'about_vision']);

require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Company Description</h2>
    <p><?php echo nl2br(sanitize_output($about['content'] ?? 'About content is coming soon.')); ?></p>
</section>

<section class="grid grid-2">
    <article class="card">
        <h3>Mission</h3>
        <p><?php echo nl2br(sanitize_output($mission['content'] ?? 'Our mission details are being updated.')); ?></p>
    </article>
    <article class="card">
        <h3>Vision</h3>
        <p><?php echo nl2br(sanitize_output($vision['content'] ?? 'Our vision details are being updated.')); ?></p>
    </article>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
