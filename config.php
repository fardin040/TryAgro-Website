<?php

declare(strict_types=1);

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', __DIR__);
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'TryAgro Website');
}

if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME') ?: 'tryagro');
}

if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER') ?: 'root');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS') ?: '');
}

if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * Get a singleton PDO connection.
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);

    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}

/**
 * Execute a prepared SELECT statement and fetch all rows.
 *
 * @param array<int|string, mixed> $params
 * @return array<int, array<string, mixed>>
 */
function db_fetch_all(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

/**
 * Execute a prepared SELECT statement and fetch one row.
 *
 * @param array<int|string, mixed> $params
 * @return array<string, mixed>|null
 */
function db_fetch_one(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    $row = $stmt->fetch();

    return $row === false ? null : $row;
}

/**
 * Execute a prepared INSERT/UPDATE/DELETE statement.
 *
 * @param array<int|string, mixed> $params
 */
function db_execute(string $sql, array $params = []): int
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt->rowCount();
}

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_admin_auth(): void
{
    if (!is_admin_logged_in()) {
        redirect('/admin/login.php');
    }
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

/**
 * @param scalar|null $value
 */
function sanitize_output($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate required form fields.
 *
 * @param array<string, mixed> $data
 * @param array<int, string> $requiredFields
 * @return array<string, string>
 */
function validate_required_fields(array $data, array $requiredFields): array
{
    $errors = [];

    foreach ($requiredFields as $field) {
        $value = $data[$field] ?? null;
        if ($value === null || trim((string) $value) === '') {
            $errors[$field] = 'This field is required.';
        }
    }

    return $errors;
}

function validate_email_format(string $email): ?string
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return 'Please provide a valid email address.';
    }

    return null;
}

/**
 * Validate an uploaded image by mime type and size.
 *
 * @param array<string, mixed> $file Typically one element from $_FILES.
 * @param array<int, string> $allowedMimeTypes
 * @return array<int, string>
 */
function validate_uploaded_image(
    array $file,
    array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'],
    int $maxSizeBytes = 2_097_152
): array {
    $errors = [];

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $errors[] = 'Image upload failed.';
        return $errors;
    }

    if (($file['size'] ?? 0) > $maxSizeBytes) {
        $errors[] = sprintf('Image must be %d MB or smaller.', (int) ($maxSizeBytes / 1024 / 1024));
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    $mimeType = $tmpName !== '' ? (string) mime_content_type($tmpName) : '';

    if ($mimeType === '' || !in_array($mimeType, $allowedMimeTypes, true)) {
        $errors[] = 'Invalid image type. Allowed: JPG, PNG, WEBP.';
    }

    return $errors;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');

    return $sessionToken !== '' && $token !== null && hash_equals($sessionToken, $token);
}

function require_valid_csrf_or_redirect(string $redirectPath): void
{
    $token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : null;

    if (!verify_csrf_token($token)) {
        $_SESSION['flash_error'] = 'Invalid CSRF token. Please try again.';
        redirect($redirectPath);
    }
}

function set_flash_message(string $type, string $message): void
{
    $_SESSION['flash_' . $type] = $message;
}

function get_flash_message(string $type): ?string
{
    $key = 'flash_' . $type;
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $message = (string) $_SESSION[$key];
    unset($_SESSION[$key]);

    return $message;
}
