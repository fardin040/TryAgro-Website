<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Home | ' . SITE_NAME;

$slides = db_fetch_all("SELECT title, youtube_link FROM videos ORDER BY id DESC LIMIT 3");
$intro = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'homepage_intro']);
$featuredCategories = db_fetch_all('SELECT id, name FROM categories ORDER BY id DESC LIMIT 6');
$latestVideos = db_fetch_all('SELECT id, title, youtube_link FROM videos ORDER BY id DESC LIMIT 4');
$footerContact = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'contact_details']);

require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Hero Slider</h2>
    <?php if ($slides !== []) : ?>
        <?php foreach ($slides as $slide) : ?>
            <article class="hero-slide">
                <h3><?php echo sanitize_output($slide['title']); ?></h3>
                <p class="muted">Video highlight: <?php echo sanitize_output($slide['youtube_link']); ?></p>
            </article>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="muted">No hero slides available yet.</p>
    <?php endif; ?>
</section>

<section>
    <h2>Intro</h2>
    <p><?php echo nl2br(sanitize_output($intro['content'] ?? 'Welcome to TryAgro.')); ?></p>
</section>

<section>
    <h2>Featured Categories</h2>
    <div class="grid grid-3">
        <?php foreach ($featuredCategories as $category) : ?>
            <article class="card">
                <h3><?php echo sanitize_output($category['name']); ?></h3>
                <a href="/products.php?category_id=<?php echo (int) $category['id']; ?>">View products</a>
            </article>
        <?php endforeach; ?>
        <?php if ($featuredCategories === []) : ?>
            <p class="muted">No categories available.</p>
        <?php endif; ?>
    </div>
</section>

<section>
    <h2>Latest Videos</h2>
    <div class="grid grid-2">
        <?php foreach ($latestVideos as $video) : ?>
            <article class="card">
                <h3><?php echo sanitize_output($video['title']); ?></h3>
                <p class="muted"><?php echo sanitize_output($video['youtube_link']); ?></p>
            </article>
        <?php endforeach; ?>
        <?php if ($latestVideos === []) : ?>
            <p class="muted">No video posts yet.</p>
        <?php endif; ?>
    </div>
</section>

<section class="card">
    <h2>Footer Contact Block</h2>
    <p><?php echo nl2br(sanitize_output($footerContact['content'] ?? 'Contact info will be published soon.')); ?></p>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
