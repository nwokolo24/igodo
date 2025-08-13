<?php
require __DIR__ . '/../vendor/autoload.php';

use App\RequestLogger;

$logger = new RequestLogger(__DIR__ . '/../logs/requests.log');
$logger->log();

// Your app logic here
echo "Hello from funcityng.com";
