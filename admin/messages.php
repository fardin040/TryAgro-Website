<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_admin_auth();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf_or_redirect('/admin/messages.php');

    $action = (string) ($_POST['action'] ?? '');
    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $errors[] = 'Invalid message selected.';
        }

        if ($errors === []) {
            db_execute('DELETE FROM messages WHERE id = :id', [':id' => $id]);
            set_flash_message('success', 'Message deleted successfully.');
            redirect('/admin/messages.php');
        }
    }
}

$messages = db_fetch_all('SELECT id, name, email, message, created_at FROM messages ORDER BY created_at DESC');
$success = get_flash_message('success');
$errorFlash = get_flash_message('error');
$pageTitle = 'Admin Messages | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Contact Messages</h2>
    <p><a href="/admin/dashboard.php">&larr; Dashboard</a> | <a href="/admin/logout.php">Logout</a></p>

    <?php if ($success !== null): ?><p style="color:#0a0;"><?php echo sanitize_output($success); ?></p><?php endif; ?>
    <?php if ($errorFlash !== null): ?><p style="color:#a00;"><?php echo sanitize_output($errorFlash); ?></p><?php endif; ?>
    <?php foreach ($errors as $error): ?><p style="color:#a00;"><?php echo sanitize_output($error); ?></p><?php endforeach; ?>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Received</th><th>Action</th></tr>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?php echo sanitize_output((string) $message['id']); ?></td>
                <td><?php echo sanitize_output($message['name']); ?></td>
                <td><?php echo sanitize_output($message['email']); ?></td>
                <td><?php echo nl2br(sanitize_output($message['message'])); ?></td>
                <td><?php echo sanitize_output($message['created_at']); ?></td>
                <td>
                    <form method="post" action="/admin/messages.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo sanitize_output((string) $message['id']); ?>">
                        <button type="submit" onclick="return confirm('Delete this message?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
