<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_admin_auth();

$errors = [];
$editingProduct = null;

/**
 * @return string|null
 */
function handle_product_image_upload(array $file): ?string
{
    $uploadDir = PROJECT_ROOT . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $originalName = (string) ($file['name'] ?? 'image');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if ($extension === '') {
        $extension = 'jpg';
    }

    $filename = bin2hex(random_bytes(8)) . '.' . preg_replace('/[^a-z0-9]+/i', '', $extension);
    $targetPath = $uploadDir . '/' . $filename;

    if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
        return null;
    }

    return '/uploads/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf_or_redirect('/admin/products.php');
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'create' || $action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $details = trim((string) ($_POST['details'] ?? ''));
        $keepCurrentImage = trim((string) ($_POST['current_image'] ?? ''));

        if ($action === 'update' && $id <= 0) {
            $errors[] = 'Invalid product selected.';
        }
        if ($categoryId <= 0) {
            $errors[] = 'Category is required.';
        }
        if ($name === '') {
            $errors[] = 'Product name is required.';
        }

        $imagePath = $keepCurrentImage !== '' ? $keepCurrentImage : null;
        $hasFile = isset($_FILES['image']) && (int) $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($hasFile) {
            $uploadErrors = validate_uploaded_image($_FILES['image']);
            if ($uploadErrors !== []) {
                $errors = array_merge($errors, $uploadErrors);
            }
        }

        if ($errors === [] && $hasFile) {
            $uploadedImagePath = handle_product_image_upload($_FILES['image']);
            if ($uploadedImagePath === null) {
                $errors[] = 'Failed to save uploaded image.';
            } else {
                $imagePath = $uploadedImagePath;
            }
        }

        if ($errors === []) {
            if ($action === 'create') {
                db_execute(
                    'INSERT INTO products (category_id, name, image, description, details) VALUES (:category_id, :name, :image, :description, :details)',
                    [
                        ':category_id' => $categoryId,
                        ':name' => $name,
                        ':image' => $imagePath,
                        ':description' => $description !== '' ? $description : null,
                        ':details' => $details !== '' ? $details : null,
                    ]
                );
                set_flash_message('success', 'Product added successfully.');
            } else {
                db_execute(
                    'UPDATE products SET category_id = :category_id, name = :name, image = :image, description = :description, details = :details WHERE id = :id',
                    [
                        ':category_id' => $categoryId,
                        ':name' => $name,
                        ':image' => $imagePath,
                        ':description' => $description !== '' ? $description : null,
                        ':details' => $details !== '' ? $details : null,
                        ':id' => $id,
                    ]
                );
                set_flash_message('success', 'Product updated successfully.');
            }
            redirect('/admin/products.php');
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $errors[] = 'Invalid product selected.';
        }

        if ($errors === []) {
            $product = db_fetch_one('SELECT image FROM products WHERE id = :id', [':id' => $id]);
            db_execute('DELETE FROM products WHERE id = :id', [':id' => $id]);

            $image = (string) ($product['image'] ?? '');
            if ($image !== '' && str_starts_with($image, '/uploads/')) {
                $path = PROJECT_ROOT . $image;
                if (is_file($path)) {
                    unlink($path);
                }
            }

            set_flash_message('success', 'Product deleted successfully.');
            redirect('/admin/products.php');
        }
    }
}

$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editingProduct = db_fetch_one('SELECT * FROM products WHERE id = :id', [':id' => $editId]);
}

$categories = db_fetch_all('SELECT id, name FROM categories ORDER BY name ASC');
$products = db_fetch_all(
    'SELECT p.id, p.name, p.image, p.description, p.category_id, c.name AS category_name
     FROM products p
     JOIN categories c ON c.id = p.category_id
     ORDER BY p.id DESC'
);

$success = get_flash_message('success');
$errorFlash = get_flash_message('error');
$pageTitle = 'Admin Products | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Products</h2>
    <p><a href="/admin/dashboard.php">&larr; Dashboard</a> | <a href="/admin/logout.php">Logout</a></p>

    <?php if ($success !== null): ?><p style="color:#0a0;"><?php echo sanitize_output($success); ?></p><?php endif; ?>
    <?php if ($errorFlash !== null): ?><p style="color:#a00;"><?php echo sanitize_output($errorFlash); ?></p><?php endif; ?>
    <?php foreach ($errors as $error): ?><p style="color:#a00;"><?php echo sanitize_output($error); ?></p><?php endforeach; ?>

    <h3><?php echo $editingProduct !== null ? 'Edit Product' : 'Add Product'; ?></h3>
    <form method="post" action="/admin/products.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
        <input type="hidden" name="action" value="<?php echo $editingProduct !== null ? 'update' : 'create'; ?>">
        <?php if ($editingProduct !== null): ?>
            <input type="hidden" name="id" value="<?php echo sanitize_output((string) $editingProduct['id']); ?>">
            <input type="hidden" name="current_image" value="<?php echo sanitize_output((string) ($editingProduct['image'] ?? '')); ?>">
        <?php endif; ?>

        <label for="category_id">Category</label>
        <select id="category_id" name="category_id" required>
            <option value="">Select category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo sanitize_output((string) $category['id']); ?>" <?php echo ((int) ($editingProduct['category_id'] ?? 0) === (int) $category['id']) ? 'selected' : ''; ?>>
                    <?php echo sanitize_output($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="name">Product Name</label>
        <input id="name" name="name" type="text" maxlength="200" required value="<?php echo sanitize_output($editingProduct['name'] ?? ''); ?>">

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3"><?php echo sanitize_output($editingProduct['description'] ?? ''); ?></textarea>

        <label for="details">Details</label>
        <textarea id="details" name="details" rows="5"><?php echo sanitize_output($editingProduct['details'] ?? ''); ?></textarea>

        <label for="image">Image (JPG/PNG/WEBP, max 2MB)</label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" <?php echo $editingProduct === null ? 'required' : ''; ?>>

        <button type="submit"><?php echo $editingProduct !== null ? 'Update' : 'Add'; ?></button>
        <?php if ($editingProduct !== null): ?><a href="/admin/products.php">Cancel</a><?php endif; ?>
    </form>

    <h3>All Products</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>ID</th><th>Name</th><th>Category</th><th>Image</th><th>Actions</th></tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo sanitize_output((string) $product['id']); ?></td>
                <td><?php echo sanitize_output($product['name']); ?></td>
                <td><?php echo sanitize_output($product['category_name']); ?></td>
                <td>
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo sanitize_output($product['image']); ?>" alt="Product image" width="80">
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/admin/products.php?edit=<?php echo sanitize_output((string) $product['id']); ?>">Edit</a>
                    <form method="post" action="/admin/products.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo sanitize_output((string) $product['id']); ?>">
                        <button type="submit" onclick="return confirm('Delete this product?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
