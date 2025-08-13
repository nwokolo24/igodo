# Igodo - PHP Request Logger

A simple PHP library for comprehensive HTTP request logging using Monolog.

## Features

- Logs all request details (headers, GET/POST data, cookies, etc.)
- Uses Monolog for structured JSON logging
- Generates unique request IDs for request tracking
- Adds request ID response header for client-side correlation
- Tracks memory usage and request duration

## Installation

### From GitHub (Recommended)

Add the repository to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/nwokolo24/igodo"
        }
    ],
    "require": {
        "nwokolo24/igodo": "dev-main"
    }
}
```

Or use these commands:

```bash
composer config repositories.igodo vcs https://github.com/nwokolo24/igodo
composer require nwokolo24/igodo:dev-main
```

### Using a Specific Version

When we create releases with version tags, you can require a specific version:

```bash
composer require nwokolo24/igodo:^1.0
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
