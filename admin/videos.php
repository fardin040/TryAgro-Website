<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_admin_auth();

$errors = [];
$editingVideo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf_or_redirect('/admin/videos.php');
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'create' || $action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim((string) ($_POST['title'] ?? ''));
        $youtubeLink = trim((string) ($_POST['youtube_link'] ?? ''));

        if ($action === 'update' && $id <= 0) {
            $errors[] = 'Invalid video selected.';
        }
        if ($title === '') {
            $errors[] = 'Title is required.';
        }
        if ($youtubeLink === '') {
            $errors[] = 'YouTube link is required.';
        } elseif (filter_var($youtubeLink, FILTER_VALIDATE_URL) === false || stripos($youtubeLink, 'youtube.com') === false && stripos($youtubeLink, 'youtu.be') === false) {
            $errors[] = 'Please provide a valid YouTube URL.';
        }

        if ($errors === []) {
            if ($action === 'create') {
                db_execute('INSERT INTO videos (title, youtube_link) VALUES (:title, :youtube_link)', [
                    ':title' => $title,
                    ':youtube_link' => $youtubeLink,
                ]);
                set_flash_message('success', 'Video added successfully.');
            } else {
                db_execute('UPDATE videos SET title = :title, youtube_link = :youtube_link WHERE id = :id', [
                    ':title' => $title,
                    ':youtube_link' => $youtubeLink,
                    ':id' => $id,
                ]);
                set_flash_message('success', 'Video updated successfully.');
            }
            redirect('/admin/videos.php');
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $errors[] = 'Invalid video selected.';
        }

        if ($errors === []) {
            db_execute('DELETE FROM videos WHERE id = :id', [':id' => $id]);
            set_flash_message('success', 'Video deleted successfully.');
            redirect('/admin/videos.php');
        }
    }
}

$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editingVideo = db_fetch_one('SELECT * FROM videos WHERE id = :id', [':id' => $editId]);
}

$videos = db_fetch_all('SELECT * FROM videos ORDER BY id DESC');
$success = get_flash_message('success');
$errorFlash = get_flash_message('error');
$pageTitle = 'Admin Videos | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Videos</h2>
    <p><a href="/admin/dashboard.php">&larr; Dashboard</a> | <a href="/admin/logout.php">Logout</a></p>

    <?php if ($success !== null): ?><p style="color:#0a0;"><?php echo sanitize_output($success); ?></p><?php endif; ?>
    <?php if ($errorFlash !== null): ?><p style="color:#a00;"><?php echo sanitize_output($errorFlash); ?></p><?php endif; ?>
    <?php foreach ($errors as $error): ?><p style="color:#a00;"><?php echo sanitize_output($error); ?></p><?php endforeach; ?>

    <h3><?php echo $editingVideo !== null ? 'Edit Video' : 'Add Video'; ?></h3>
    <form method="post" action="/admin/videos.php">
        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
        <input type="hidden" name="action" value="<?php echo $editingVideo !== null ? 'update' : 'create'; ?>">
        <?php if ($editingVideo !== null): ?>
            <input type="hidden" name="id" value="<?php echo sanitize_output((string) $editingVideo['id']); ?>">
        <?php endif; ?>

        <label for="title">Title</label>
        <input id="title" name="title" type="text" maxlength="200" required value="<?php echo sanitize_output($editingVideo['title'] ?? ''); ?>">

        <label for="youtube_link">YouTube Link</label>
        <input id="youtube_link" name="youtube_link" type="url" maxlength="255" required value="<?php echo sanitize_output($editingVideo['youtube_link'] ?? ''); ?>">

        <button type="submit"><?php echo $editingVideo !== null ? 'Update' : 'Add'; ?></button>
        <?php if ($editingVideo !== null): ?><a href="/admin/videos.php">Cancel</a><?php endif; ?>
    </form>

    <h3>All Videos</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>ID</th><th>Title</th><th>YouTube Link</th><th>Actions</th></tr>
        <?php foreach ($videos as $video): ?>
            <tr>
                <td><?php echo sanitize_output((string) $video['id']); ?></td>
                <td><?php echo sanitize_output($video['title']); ?></td>
                <td><a href="<?php echo sanitize_output($video['youtube_link']); ?>" target="_blank" rel="noopener noreferrer">View</a></td>
                <td>
                    <a href="/admin/videos.php?edit=<?php echo sanitize_output((string) $video['id']); ?>">Edit</a>
                    <form method="post" action="/admin/videos.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo sanitize_output((string) $video['id']); ?>">
                        <button type="submit" onclick="return confirm('Delete this video?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
