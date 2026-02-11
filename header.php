<?php

declare(strict_types=1);

require_once PROJECT_ROOT . '/config.php';

$pageTitle = $pageTitle ?? SITE_NAME;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; color: #1f2937; }
        header, footer { background: #f3f4f6; }
        .container { width: min(1100px, 92%); margin: 0 auto; }
        header .container { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
        nav a { margin-right: .75rem; text-decoration: none; color: #065f46; font-weight: 600; }
        nav a:last-child { margin-right: 0; }
        main .container { padding: 1.2rem 0 2rem; }
        .grid { display: grid; gap: 1rem; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 1rem; background: #fff; }
        .muted { color: #4b5563; }
        .hero-slide { padding: 1.5rem; background: #ecfdf5; border-radius: 12px; margin-bottom: .75rem; }
        .alert-success { border-left: 4px solid #16a34a; padding: .6rem .8rem; background: #f0fdf4; }
        .alert-error { border-left: 4px solid #dc2626; padding: .6rem .8rem; background: #fef2f2; }
        label { display: block; margin-bottom: .25rem; font-weight: 600; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: .55rem; margin-bottom: .75rem; }
        button { padding: .6rem 1rem; border: 0; border-radius: 8px; background: #065f46; color: white; cursor: pointer; }
        iframe { width: 100%; min-height: 220px; border: 0; }
        footer .container { padding: 1rem 0; }
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1><?php echo SITE_NAME; ?></h1>
        <nav>
            <a href="/index.php">Home</a>
            <a href="/about.php">About</a>
            <a href="/products.php">Products</a>
            <a href="/network.php">Network</a>
            <a href="/videos.php">Videos</a>
            <a href="/contact.php">Contact</a>
        </nav>
    </div>
</header>
<main>
    <div class="container">
