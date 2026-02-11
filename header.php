<?php

declare(strict_types=1);

require_once PROJECT_ROOT . '/config.php';

$pageTitle = $pageTitle ?? SITE_NAME;
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php', '.php');

$navItems = [
    'index' => 'Home',
    'about' => 'About',
    'products' => 'Products',
    'videos' => 'Videos',
    'network' => 'Network',
    'contact' => 'Contact',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="/index.php"><?php echo SITE_NAME; ?></a>
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
