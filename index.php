<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Home | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section class="hero-slider" aria-label="Featured highlights">
    <article class="hero-slide is-active" data-slide>
        <div class="container">
            <p class="eyebrow">Sustainable farming</p>
            <h1>Smart solutions for modern agriculture</h1>
            <p>We help growers improve yield, cut waste, and build resilient farm systems.</p>
            <a class="btn" href="/products.php">Explore products</a>
        </div>
    </article>
    <article class="hero-slide" data-slide>
        <div class="container">
            <p class="eyebrow">Trusted expertise</p>
            <h2>Field-tested strategies for every season</h2>
            <p>From crop planning to soil health, our team supports your success end-to-end.</p>
            <a class="btn" href="/network.php">Find our network</a>
        </div>
    </article>
    <article class="hero-slide" data-slide>
        <div class="container">
            <p class="eyebrow">Built for growth</p>
            <h2>Tools and guidance tailored to your farm</h2>
            <p>Access practical recommendations and products designed for real field conditions.</p>
            <a class="btn" href="/contact.php">Talk to us</a>
        </div>
    </article>
    <div class="slider-dots" role="tablist" aria-label="Hero slides"></div>
</section>

<section class="section container">
    <h2>Why growers choose TryAgro</h2>
    <div class="card-grid">
        <article class="card">
            <h3>Crop-specific plans</h3>
            <p>Actionable recommendations based on climate, soil profile, and crop stage.</p>
        </article>
        <article class="card">
            <h3>Reliable inputs</h3>
            <p>Quality-first products sourced for consistent performance and safety standards.</p>
        </article>
        <article class="card">
            <h3>Local support</h3>
            <p>Dealer network and agronomy guidance to keep your operations moving forward.</p>
        </article>
    </div>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
