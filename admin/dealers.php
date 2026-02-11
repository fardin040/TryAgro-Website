<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_admin_auth();

$errors = [];
$editingDealer = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf_or_redirect('/admin/dealers.php');
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'create' || $action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $region = trim((string) ($_POST['region'] ?? ''));
        $district = trim((string) ($_POST['district'] ?? ''));
        $name = trim((string) ($_POST['name'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));

        if ($action === 'update' && $id <= 0) {
            $errors[] = 'Invalid dealer selected.';
        }
        if ($region === '') {
            $errors[] = 'Region is required.';
        }
        if ($district === '') {
            $errors[] = 'District is required.';
        }
        if ($name === '') {
            $errors[] = 'Dealer name is required.';
        }

        if ($errors === []) {
            if ($action === 'create') {
                db_execute(
                    'INSERT INTO dealers (region, district, name, address, phone) VALUES (:region, :district, :name, :address, :phone)',
                    [
                        ':region' => $region,
                        ':district' => $district,
                        ':name' => $name,
                        ':address' => $address !== '' ? $address : null,
                        ':phone' => $phone !== '' ? $phone : null,
                    ]
                );
                set_flash_message('success', 'Dealer added successfully.');
            } else {
                db_execute(
                    'UPDATE dealers SET region = :region, district = :district, name = :name, address = :address, phone = :phone WHERE id = :id',
                    [
                        ':region' => $region,
                        ':district' => $district,
                        ':name' => $name,
                        ':address' => $address !== '' ? $address : null,
                        ':phone' => $phone !== '' ? $phone : null,
                        ':id' => $id,
                    ]
                );
                set_flash_message('success', 'Dealer updated successfully.');
            }
            redirect('/admin/dealers.php');
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $errors[] = 'Invalid dealer selected.';
        }

        if ($errors === []) {
            db_execute('DELETE FROM dealers WHERE id = :id', [':id' => $id]);
            set_flash_message('success', 'Dealer deleted successfully.');
            redirect('/admin/dealers.php');
        }
    }
}

$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editingDealer = db_fetch_one('SELECT * FROM dealers WHERE id = :id', [':id' => $editId]);
}

$dealers = db_fetch_all('SELECT * FROM dealers ORDER BY id DESC');
$success = get_flash_message('success');
$errorFlash = get_flash_message('error');
$pageTitle = 'Admin Dealers | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Dealers</h2>
    <p><a href="/admin/dashboard.php">&larr; Dashboard</a> | <a href="/admin/logout.php">Logout</a></p>

    <?php if ($success !== null): ?><p style="color:#0a0;"><?php echo sanitize_output($success); ?></p><?php endif; ?>
    <?php if ($errorFlash !== null): ?><p style="color:#a00;"><?php echo sanitize_output($errorFlash); ?></p><?php endif; ?>
    <?php foreach ($errors as $error): ?><p style="color:#a00;"><?php echo sanitize_output($error); ?></p><?php endforeach; ?>

    <h3><?php echo $editingDealer !== null ? 'Edit Dealer' : 'Add Dealer'; ?></h3>
    <form method="post" action="/admin/dealers.php">
        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
        <input type="hidden" name="action" value="<?php echo $editingDealer !== null ? 'update' : 'create'; ?>">
        <?php if ($editingDealer !== null): ?>
            <input type="hidden" name="id" value="<?php echo sanitize_output((string) $editingDealer['id']); ?>">
        <?php endif; ?>

        <label for="region">Region</label>
        <input id="region" name="region" type="text" maxlength="120" required value="<?php echo sanitize_output($editingDealer['region'] ?? ''); ?>">

        <label for="district">District</label>
        <input id="district" name="district" type="text" maxlength="120" required value="<?php echo sanitize_output($editingDealer['district'] ?? ''); ?>">

        <label for="name">Dealer Name</label>
        <input id="name" name="name" type="text" maxlength="180" required value="<?php echo sanitize_output($editingDealer['name'] ?? ''); ?>">

        <label for="address">Address</label>
        <input id="address" name="address" type="text" maxlength="255" value="<?php echo sanitize_output($editingDealer['address'] ?? ''); ?>">

        <label for="phone">Phone</label>
        <input id="phone" name="phone" type="text" maxlength="30" value="<?php echo sanitize_output($editingDealer['phone'] ?? ''); ?>">

        <button type="submit"><?php echo $editingDealer !== null ? 'Update' : 'Add'; ?></button>
        <?php if ($editingDealer !== null): ?><a href="/admin/dealers.php">Cancel</a><?php endif; ?>
    </form>

    <h3>All Dealers</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>ID</th><th>Region</th><th>District</th><th>Name</th><th>Phone</th><th>Actions</th></tr>
        <?php foreach ($dealers as $dealer): ?>
            <tr>
                <td><?php echo sanitize_output((string) $dealer['id']); ?></td>
                <td><?php echo sanitize_output($dealer['region']); ?></td>
                <td><?php echo sanitize_output($dealer['district']); ?></td>
                <td><?php echo sanitize_output($dealer['name']); ?></td>
                <td><?php echo sanitize_output($dealer['phone']); ?></td>
                <td>
                    <a href="/admin/dealers.php?edit=<?php echo sanitize_output((string) $dealer['id']); ?>">Edit</a>
                    <form method="post" action="/admin/dealers.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo sanitize_output((string) $dealer['id']); ?>">
                        <button type="submit" onclick="return confirm('Delete this dealer?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
