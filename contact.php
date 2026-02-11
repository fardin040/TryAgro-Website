<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Contact | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section class="container section">
    <p class="eyebrow">Get in touch</p>
    <h1>Speak with our team</h1>
    <p>Have a question about products, availability, or agronomy support? Send us a message.</p>

    <form class="form-card" action="#" method="post">
        <label for="name">Name</label>
        <input id="name" name="name" type="text" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>

        <label for="topic">Topic</label>
        <select id="topic" name="topic">
            <option>Product information</option>
            <option>Dealer inquiry</option>
            <option>Technical support</option>
        </select>

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button class="btn" type="submit">Send message</button>
    </form>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
