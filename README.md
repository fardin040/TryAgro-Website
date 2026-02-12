# TryAgro Website

This is a PHP + MySQL website project for TryAgro, with a public website and an admin area.

## Requirements

Before running locally or deploying, make sure your environment has:

- **PHP 8.1+** (recommended: PHP 8.2)
- **MySQL 8+** or **MariaDB 10.4+**
- PHP extensions:
  - `pdo`
  - `pdo_mysql`
  - `mbstring`
  - `json`
  - `fileinfo`
  - `session` (usually enabled by default)

> Shared hosting note: On cPanel/InfinityFree, choose a PHP version that supports PDO MySQL (PHP 8.1+ recommended).

## Setup Guide

### 1) Upload/clone project files

Place the project in your web root:

- cPanel usually: `public_html/`
- InfinityFree usually: `htdocs/`

### 2) Create the database

1. Create a MySQL database and user from your hosting control panel.
2. Assign the user to the database with **ALL PRIVILEGES**.

### 3) Import `database.sql`

You can import using **phpMyAdmin**:

1. Open phpMyAdmin.
2. Select your target database.
3. Go to **Import**.
4. Choose `database.sql` from this project.
5. Click **Go**.

Or from command line:

```bash
mysql -u your_db_user -p your_db_name < database.sql
```

### 4) Configure DB credentials in `config.php`

Edit these values (or set matching environment variables):

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

Example:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_db_name');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
```

> Important: `database.sql` creates `tryagro_website` by default. If your hosting enforces a prefixed DB name, update `DB_NAME` in `config.php` to the exact name you created (for example `if0_12345678_tryagro_website`).

### 5) Create writable `uploads/` permissions

The `uploads/` directory must be writable by PHP for image/file uploads.

Typical permission settings:

- Directory: `755` (preferred when server user ownership is correct)
- If uploads fail on shared hosting: `775` or `777` temporarily for testing

Command line example:

```bash
chmod 755 uploads
```

In cPanel File Manager, right-click `uploads` → **Change Permissions**.

## Default Admin Credentials

After importing `database.sql`, default admin login is:

- **Username:** `admin`
- **Password:** `admin123`

⚠️ **Security recommendation:** Change the admin password immediately after first login.

## cPanel / InfinityFree Deployment Steps

1. Zip project files locally (optional but faster upload).
2. Upload to:
   - cPanel: `public_html`
   - InfinityFree: `htdocs`
3. Extract files so `index.php` is directly in web root.
4. Create DB + DB user from hosting panel.
5. Import `database.sql` via phpMyAdmin.
6. Update DB credentials in `config.php`.
7. Ensure `uploads/` is writable.
8. Visit your domain and verify pages load.

## Common Troubleshooting

- **“Database connection failed” / PDO errors**
  - Re-check `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` in `config.php`.
  - On shared hosting, `DB_HOST` is often `localhost` (not always `127.0.0.1`).

- **“Access denied for user …”**
  - Ensure DB user is assigned to the DB.
  - Confirm password and DB username prefix required by host.

- **Import fails in phpMyAdmin**
  - Ensure you selected the correct database before import.
  - Re-upload `database.sql` and retry.
  - If max upload size is small, import via SSH/terminal if available.

- **Uploads not working**
  - Verify `uploads/` exists and is writable.
  - Ensure PHP `fileinfo` extension is enabled.
  - Check hosting limits: `upload_max_filesize` and `post_max_size`.

- **404 / blank page after upload**
  - Confirm files were extracted into correct web root (`public_html` / `htdocs`).
  - Check `.htaccess` rules (if added later).
  - Review host error logs for PHP fatal errors.

## Project Files of Interest

- `config.php` → database + helper config
- `database.sql` → schema + seed data
- `uploads/` → writable upload storage
