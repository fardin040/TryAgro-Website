<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    session_start();
    set_flash_message('success', 'You have been logged out.');
    redirect('/admin/login.php');
}

if (is_admin_logged_in()) {
    redirect('/admin/dashboard.php');
}

$error = get_flash_message('error');
$success = get_flash_message('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf_or_redirect('/admin/login.php');

    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        $admin = db_fetch_one('SELECT id, username, password FROM admins WHERE username = :username LIMIT 1', [
            ':username' => $username,
        ]);

        if ($admin !== null && password_verify($password, (string) $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = (int) $admin['id'];
            $_SESSION['admin_username'] = (string) $admin['username'];
            redirect('/admin/dashboard.php');
        }

        $error = 'Invalid username or password.';
    }
}

$pageTitle = 'Admin Login | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Admin Login</h2>

    <?php if ($error !== null): ?>
        <p style="color: #a00;"><?php echo sanitize_output($error); ?></p>
    <?php endif; ?>

    <?php if ($success !== null): ?>
        <p style="color: #0a0;"><?php echo sanitize_output($success); ?></p>
    <?php endif; ?>

    <form method="post" action="/admin/login.php">
        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output(csrf_token()); ?>">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" maxlength="100" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Sign In</button>
    </form>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
