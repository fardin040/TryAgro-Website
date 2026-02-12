<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_admin_auth();

$errors = [];
$editingCategory = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf_or_redirect('/admin/categories.php');
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'create') {
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            $errors[] = 'Category name is required.';
        }

        if ($errors === []) {
            db_execute('INSERT INTO categories (name) VALUES (:name)', [':name' => $name]);
            set_flash_message('success', 'Category added successfully.');
            redirect('/admin/categories.php');
        }
    }

    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));

        if ($id <= 0) {
            $errors[] = 'Invalid category selected.';
        }
        if ($name === '') {
            $errors[] = 'Category name is required.';
        }

        if ($errors === []) {
            db_execute('UPDATE categories SET name = :name WHERE id = :id', [
                ':name' => $name,
                ':id' => $id,
            ]);
            set_flash_message('success', 'Category updated successfully.');
            redirect('/admin/categories.php');
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $errors[] = 'Invalid category selected.';
        }

        if ($errors === []) {
            db_execute('DELETE FROM categories WHERE id = :id', [':id' => $id]);
            set_flash_message('success', 'Category deleted successfully.');
            redirect('/admin/categories.php');
        }
    }
}

$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editingCategory = db_fetch_one('SELECT id, name FROM categories WHERE id = :id', [':id' => $editId]);
}

$categories = db_fetch_all('SELECT id, name FROM categories ORDER BY name ASC');
$success = get_flash_message('success');
$errorFlash = get_flash_message('error');
$pageTitle = 'Admin Categories | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Categories</h2>
    <p><a href="/admin/dashboard.php">&larr; Dashboard</a> | <a href="/admin/logout.php">Logout</a></p>

    <?php if ($success !== null): ?><p style="color:#0a0;"><?php echo sanitize_output($success); ?></p><?php endif; ?>
    <?php if ($errorFlash !== null): ?><p style="color:#a00;"><?php echo sanitize_output($errorFlash); ?></p><?php endif; ?>
    <?php foreach ($errors as $error): ?><p style="color:#a00;"><?php echo sanitize_output($error); ?></p><?php endforeach; ?>

    <h3><?php echo $editingCategory !== null ? 'Edit Category' : 'Add Category'; ?></h3>
    <form method="post" action="/admin/categories.php">
        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
        <input type="hidden" name="action" value="<?php echo $editingCategory !== null ? 'update' : 'create'; ?>">
        <?php if ($editingCategory !== null): ?>
            <input type="hidden" name="id" value="<?php echo sanitize_output((string) $editingCategory['id']); ?>">
        <?php endif; ?>
        <label for="name">Name</label>
        <input id="name" name="name" type="text" maxlength="150" required value="<?php echo sanitize_output($editingCategory['name'] ?? ''); ?>">
        <button type="submit"><?php echo $editingCategory !== null ? 'Update' : 'Add'; ?></button>
        <?php if ($editingCategory !== null): ?><a href="/admin/categories.php">Cancel</a><?php endif; ?>
    </form>

    <h3>All Categories</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo sanitize_output((string) $category['id']); ?></td>
                <td><?php echo sanitize_output($category['name']); ?></td>
                <td>
                    <a href="/admin/categories.php?edit=<?php echo sanitize_output((string) $category['id']); ?>">Edit</a>
                    <form method="post" action="/admin/categories.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo sanitize_output((string) $category['id']); ?>">
                        <button type="submit" onclick="return confirm('Delete this category?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
