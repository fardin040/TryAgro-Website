<?php

declare(strict_types=1);

require_once PROJECT_ROOT . '/config.php';

$pageTitle = $pageTitle ?? SITE_NAME;
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php', '.php');

$navItems = [
    'index'    => 'Home',
    'about'    => 'About',
    'products' => 'Products',
    'videos'   => 'Videos',
    'network'  => 'Network',
    'contact'  => 'Contact',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        /* ... existing styles ... */
        .site-nav ul { list-style: none; padding: 0; display: flex; gap: 1rem; }
        .site-nav a.active { color: #059669; text-decoration: underline; }
        .skip-link { position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden; }
        .skip-link:focus { position: static; width: auto; height: auto; }
    </style>
</head>
<body>

<a class="skip-link" href="#main-content">Skip to content</a>

<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="/index.php" style="font-size: 1.5rem; font-weight: bold; text-decoration: none; color: inherit;">
            <?php echo SITE_NAME; ?>
        </a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-navigation">
            <span class="menu-toggle__label">Menu</span>
            <span class="menu-toggle__icon" aria-hidden="true"></span>
        </button>

        <nav id="site-navigation" class="site-nav" aria-label="Main navigation">
            <ul>
                <?php foreach ($navItems as $slug => $label): ?>
                    <li>
                        <a href="/<?php echo $slug; ?>.php"<?php echo $currentPage === $slug ? ' class="active" aria-current="page"' : ''; ?>>
                            <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>

<main id="main-content" class="site-main">
    <div class="container">