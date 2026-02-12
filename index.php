<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Fetch dynamic content from main branch logic
$slides = db_fetch_all("SELECT title, youtube_link FROM videos ORDER BY id DESC LIMIT 3");
$intro = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'homepage_intro']);
$featuredCategories = db_fetch_all('SELECT id, name FROM categories ORDER BY id DESC LIMIT 3');
$latestVideos = db_fetch_all('SELECT id, title, youtube_link FROM videos ORDER BY id DESC LIMIT 4');

$pageTitle = 'Home | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>

<section class="hero-slider" aria-label="Featured highlights">
    <?php if ($slides !== []) : ?>
        <?php foreach ($slides as $index => $slide) : ?>
            <article class="hero-slide <?php echo $index === 0 ? 'is-active' : ''; ?>" data-slide>
                <div class="container">
                    <p class="eyebrow">Latest Video</p>
                    <h1><?php echo sanitize_output($slide['title']); ?></h1>
                    <p>Watch our latest update and field insights from our YouTube channel.</p>
                    <a class="btn" href="/videos.php">Watch now</a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php else : ?>
        <article class="hero-slide is-active" data-slide>
            <div class="container">
                <p class="eyebrow">Sustainable farming</p>
                <h1>Smart solutions for modern agriculture</h1>
                <p>Welcome to TryAgro. We help growers build resilient farm systems.</p>
                <a class="btn" href="/products.php">Explore products</a>
            </div>
        </article>
    <?php endif; ?>
    <div class="slider-dots" role="tablist" aria-label="Hero slides"></div>
</section>

<section class="section container">
    <div class="card" style="border: none; background: #f9fafb;">
        <h2>Grow with Confidence</h2>
        <p><?php echo nl2br(sanitize_output($intro['content'] ?? 'TryAgro partners with growers through every stage of the season.')); ?></p>
    </div>
</section>

<section class="section container">
    <h2>Why growers choose TryAgro</h2>
    <div class="card-grid">
        <?php foreach ($featuredCategories as $category) : ?>
            <article class="card">
                <h3><?php echo sanitize_output($category['name']); ?></h3>
                <p>Actionable recommendations and quality products for this category.</p>
                <a href="/products.php?category_id=<?php echo (int) $category['id']; ?>">View products</a>
            </article>
        <?php endforeach; ?>
        
        <?php if ($featuredCategories === []) : ?>
            <article class="card">
                <h3>Local support</h3>
                <p>Dealer network and agronomy guidance to keep your operations moving.</p>
            </article>
        <?php endif; ?>
    </div>
</section>

<section class="section container">
    <h2>Latest Field Updates</h2>
    <div class="grid grid-2">
        <?php foreach ($latestVideos as $video) : ?>
            <article class="card">
                <div class="video-placeholder" style="background: #e5e7eb; aspect-ratio: 16/9; margin-bottom: 1rem; border-radius: 4px;"></div>
                <h3><?php echo sanitize_output($video['title']); ?></h3>
                <p class="muted">Check out our YouTube link: <br> 
                   <small><?php echo sanitize_output($video['youtube_link']); ?></small>
                </p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once PROJECT_ROOT . '/footer.php'; ?>