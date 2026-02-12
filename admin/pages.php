<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_admin_auth();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf_or_redirect('/admin/pages.php');

    $homepageIntro = trim((string) ($_POST['homepage_intro'] ?? ''));
    $aboutText = trim((string) ($_POST['about_text'] ?? ''));
    $contactDetails = trim((string) ($_POST['contact_details'] ?? ''));

    if ($homepageIntro === '') {
        $errors[] = 'Homepage intro is required.';
    }
    if ($aboutText === '') {
        $errors[] = 'About text is required.';
    }
    if ($contactDetails === '') {
        $errors[] = 'Contact details are required.';
    }

    if ($errors === []) {
        $pagesToSave = [
            'homepage_intro' => $homepageIntro,
            'about_text' => $aboutText,
            'contact_details' => $contactDetails,
        ];

        foreach ($pagesToSave as $pageName => $content) {
            db_execute(
                'INSERT INTO pages (page_name, content) VALUES (:page_name, :content)
                 ON DUPLICATE KEY UPDATE content = VALUES(content)',
                [
                    ':page_name' => $pageName,
                    ':content' => $content,
                ]
            );
        }

        set_flash_message('success', 'Page content updated successfully.');
        redirect('/admin/pages.php');
    }
}

$pageRows = db_fetch_all(
    'SELECT page_name, content FROM pages WHERE page_name IN ("homepage_intro", "about_text", "contact_details")'
);

$pageValues = [
    'homepage_intro' => '',
    'about_text' => '',
    'contact_details' => '',
];

foreach ($pageRows as $row) {
    $pageName = (string) ($row['page_name'] ?? '');
    if (array_key_exists($pageName, $pageValues)) {
        $pageValues[$pageName] = (string) ($row['content'] ?? '');
    }
}

$success = get_flash_message('success');
$errorFlash = get_flash_message('error');
$pageTitle = 'Admin Pages | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Pages Content</h2>
    <p><a href="/admin/dashboard.php">&larr; Dashboard</a> | <a href="/admin/logout.php">Logout</a></p>

    <?php if ($success !== null): ?><p style="color:#0a0;"><?php echo sanitize_output($success); ?></p><?php endif; ?>
    <?php if ($errorFlash !== null): ?><p style="color:#a00;"><?php echo sanitize_output($errorFlash); ?></p><?php endif; ?>
    <?php foreach ($errors as $error): ?><p style="color:#a00;"><?php echo sanitize_output($error); ?></p><?php endforeach; ?>

    <form method="post" action="/admin/pages.php">
        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">

        <label for="homepage_intro">Homepage Intro</label>
        <textarea id="homepage_intro" name="homepage_intro" rows="5" required><?php echo sanitize_output($pageValues['homepage_intro']); ?></textarea>

        <label for="about_text">About Text</label>
        <textarea id="about_text" name="about_text" rows="8" required><?php echo sanitize_output($pageValues['about_text']); ?></textarea>

        <label for="contact_details">Contact Details</label>
        <textarea id="contact_details" name="contact_details" rows="6" required><?php echo sanitize_output($pageValues['contact_details']); ?></textarea>

        <button type="submit">Save Content</button>
    </form>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
