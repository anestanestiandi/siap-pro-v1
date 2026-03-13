<?php

// Konfigurasi Vercel read-only filesystem
putenv('APP_DEBUG=true'); 
putenv('LOG_CHANNEL=stderr');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie'); 
putenv('VIEW_COMPILED_PATH=/tmp');

require __DIR__ . '/../public/index.php';
