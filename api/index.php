<?php

putenv('APP_DEBUG=true');
putenv('LOG_CHANNEL=stderr');
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=cookie');

// Create storage directories in /tmp for Vercel read-only filesystem
$storage = '/tmp/storage';
$dirs = [
    "$storage/framework/views",
    "$storage/framework/cache/data",
    "$storage/framework/sessions",
    "$storage/logs",
    "$storage/bootstrap/cache",
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Redirect storage path for Laravel
$_ENV['VERCEL_VIRTUAL_STORAGE'] = $storage;

require __DIR__ . '/../public/index.php';
