<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Fetch dynamic contact info
$contactDetails = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'contact_details']);

$errors = [];
$successMessage = null;
$formData = ['name' => '', 'email' => '', 'topic' => '', 'message' => ''];

// Form Handling Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = trim((string) ($_POST['name'] ?? ''));
    $formData['email'] = trim((string) ($_POST['email'] ?? ''));
    $formData['topic'] = trim((string) ($_POST['topic'] ?? ''));
    $formData['message'] = trim((string) ($_POST['message'] ?? ''));

    $errors = validate_required_fields($formData, ['name', 'email', 'message']);

    if (!isset($errors['email']) && validate_email_format($formData['email']) !== null) {
        $errors['email'] = 'Please provide a valid email address.';
    }

    // Length checks for DB safety
    if (mb_strlen($formData['name']) > 150) $errors['name'] = 'Name is too long.';
    if (mb_strlen($formData['email']) > 190) $errors['email'] = 'Email is too long.';
    if (mb_strlen($formData['message']) > 5000) $errors['message'] = 'Message is too long.';

    if ($errors === []) {
        db_execute(
            'INSERT INTO messages (name, email, topic, message) VALUES (:name, :email, :topic, :message)',
            [
                ':name'    => $formData['name'],
                ':email'   => $formData['email'],
                ':topic'   => $formData['topic'],
                ':message' => $formData['message'],
            ]
        );

        $successMessage = 'Your message has been sent successfully.';
        $formData = ['name' => '', 'email' => '', 'topic' => '', 'message' => ''];
    }
}

$pageTitle = 'Contact | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>

<section class="container section">
    <div class="grid grid-2">
        <article>
            <p class="eyebrow">Get in touch</p>
            <h1>Speak with our team</h1>
            <p>Have a question about products, availability, or agronomy support? Send us a message.</p>
            
            <div class="card" style="margin-top: 2rem;">
                <h3>Office Details</h3>
                <p><?php echo nl2br(sanitize_output($contactDetails['content'] ?? 'Office details are not available yet.')); ?></p>
            </div>
        </article>

        <article>
            <form class="form-card" method="post" action="/contact.php" novalidate>
                <h2>Send a Message</h2>

                <?php if ($successMessage) : ?>
                    <p class="alert-success"><?php echo sanitize_output($successMessage); ?></p>
                <?php endif; ?>

                <?php if ($errors !== []) : ?>
                    <div class="alert-error">
                        <ul>
                            <?php foreach ($errors as $error) : ?>
                                <li><?php echo sanitize_output($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?php echo sanitize_output($formData['name']); ?>" required>

                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo sanitize_output($formData['email']); ?>" required>

                <label for="topic">Topic</label>
                <select id="topic" name="topic">
                    <option value="Product info" <?php echo $formData['topic'] === 'Product info' ? 'selected' : ''; ?>>Product information</option>
                    <option value="Dealer inquiry" <?php echo $formData['topic'] === 'Dealer inquiry' ? 'selected' : ''; ?>>Dealer inquiry</option>
                    <option value="Technical support" <?php echo $formData['topic'] === 'Technical support' ? 'selected' : ''; ?>>Technical support</option>
                </select>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required><?php echo sanitize_output($formData['message']); ?></textarea>

                <button class="btn" type="submit">Send message</button>
            </form>
        </article>
    </div>
</section>

<?php require_once PROJECT_ROOT . '/footer.php'; ?>