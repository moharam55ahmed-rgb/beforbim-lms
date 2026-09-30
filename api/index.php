<?php

declare(strict_types=1);

// Flag that we are executing in a Vercel serverless environment
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';
putenv('VERCEL=1');

// Setup writable directories in /tmp for AWS Lambda / Vercel
$storageDirectories = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/storage/bootstrap/cache',
];

foreach ($storageDirectories as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

// Route request to Laravel public entrypoint
require __DIR__.'/../public/index.php';
