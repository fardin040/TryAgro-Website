<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = 'Contact | ' . SITE_NAME;
$contactDetails = db_fetch_one('SELECT content FROM pages WHERE page_name = :name', [':name' => 'contact_details']);

$errors = [];
$successMessage = null;
$formData = [
    'name' => '',
    'email' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = trim((string) ($_POST['name'] ?? ''));
    $formData['email'] = trim((string) ($_POST['email'] ?? ''));
    $formData['message'] = trim((string) ($_POST['message'] ?? ''));

    $errors = validate_required_fields($formData, ['name', 'email', 'message']);

    if (!isset($errors['email']) && validate_email_format($formData['email']) !== null) {
        $errors['email'] = 'Please provide a valid email address.';
    }

    if (!isset($errors['name']) && mb_strlen($formData['name']) > 150) {
        $errors['name'] = 'Name must be 150 characters or fewer.';
    }

    if (!isset($errors['email']) && mb_strlen($formData['email']) > 190) {
        $errors['email'] = 'Email must be 190 characters or fewer.';
    }

    if (!isset($errors['message']) && mb_strlen($formData['message']) > 5000) {
        $errors['message'] = 'Message must be 5000 characters or fewer.';
    }

    if ($errors === []) {
        db_execute(
            'INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)',
            [
                ':name' => $formData['name'],
                ':email' => $formData['email'],
                ':message' => $formData['message'],
            ]
        );

        $successMessage = 'Your message has been sent successfully.';
        $formData = ['name' => '', 'email' => '', 'message' => ''];
    }
}

require_once PROJECT_ROOT . '/header.php';
?>
<section class="grid grid-2">
    <article class="card">
        <h2>Office Details</h2>
        <p><?php echo nl2br(sanitize_output($contactDetails['content'] ?? 'Office details are not available yet.')); ?></p>
    </article>

    <article class="card">
        <h2>Contact Form</h2>

        <?php if ($successMessage !== null) : ?>
            <p class="alert-success"><?php echo sanitize_output($successMessage); ?></p>
        <?php endif; ?>

        <?php if ($errors !== []) : ?>
            <div class="alert-error">
                <p>Please fix the following errors:</p>
                <ul>
                    <?php foreach ($errors as $field => $error) : ?>
                        <li><?php echo sanitize_output(ucfirst($field) . ': ' . $error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/contact.php" novalidate>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" maxlength="150" value="<?php echo sanitize_output($formData['name']); ?>" required>

            <label for="email">Email</label>
            <input id="email" name="email" type="email" maxlength="190" value="<?php echo sanitize_output($formData['email']); ?>" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" maxlength="5000" required><?php echo sanitize_output($formData['message']); ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </article>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
