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
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <h1><?php echo SITE_NAME; ?></h1>
</header>
<main>
