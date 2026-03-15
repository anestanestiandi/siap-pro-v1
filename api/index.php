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

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // Log error to stderr for Vercel
    $msg = sprintf(
        "Fatal Error: %s in %s:%d\nStack trace:\n%s",
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );
    error_log($msg);
    echo "<h1>A fatal error occurred</h1>";
    echo "<pre>" . htmlspecialchars($msg) . "</pre>";
}
