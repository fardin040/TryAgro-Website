<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

session_unset();
session_destroy();
session_start();
set_flash_message('success', 'You have been logged out.');
redirect('/admin/login.php');
