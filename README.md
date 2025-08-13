# Igodo - PHP Request Logger

A simple PHP library for comprehensive HTTP request logging using Monolog.

## Features

- Logs all request details (headers, GET/POST data, cookies, etc.)
- Uses Monolog for structured JSON logging
- Generates unique request IDs for request tracking
- Adds request ID response header for client-side correlation
- Tracks memory usage and request duration

## Installation

```bash
composer require nwokolo24/igodo
```

## Usage

```php
<?php
require_once 'vendor/autoload.php';

use App\RequestLogger;

// Initialize the logger with a log file path
$logger = new RequestLogger(__DIR__ . '/logs/requests.log');

// Log the current request
$logger->log();
```

## Requirements

- PHP 8.0 or higher
- Monolog library

## Testing

```bash
vendor/bin/phpunit
```

## License

MIT
