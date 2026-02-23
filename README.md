# Juzaweb Proxies Module

The Juzaweb Proxies Module is a comprehensive solution for managing, crawling, and testing proxies within your Juzaweb CMS application. It provides tools to maintain a pool of active proxies, verify their connectivity, and seamlessly integrate them into your application logic.

## Features

- **Proxy Management**: Store and manage proxy details including IP, port, protocol, and authentication credentials.
- **Automated Testing**: Schedule regular checks to ensure proxies are active and responsive.
- **On-Demand Testing**: Manually test specific proxies via Artisan commands.
- **Proxy Allocation**: Retrieve available ("free") proxies for use in your application, with concurrency handling.
- **Admin Interface**: Configure settings and view proxy status directly from the Juzaweb Admin Panel.

## Installation

You can install the package via composer:

```bash
composer require juzaweb/proxies
```

## Configuration

### Database Settings

The module uses the following settings keys which can be configured via the Juzaweb Admin Panel (Settings -> Proxies) or directly in the database:

- `proxy_test_url`: The URL used to verify proxy connectivity (Default: `https://translate.google.com`).
- `proxy_test_timeout`: The timeout duration in seconds for proxy connection tests (Default: `20`).
- `proxy_auto_test_enable`: Enable automatic hourly testing of proxies (Set to `1` to enable).

### Scheduler

To enable the automatic proxy checker, ensure your application's scheduler is running. If `proxy_auto_test_enable` is set to `1`, the module will register a scheduled command to check proxies hourly.

```bash
php artisan schedule:run
```

## Usage

### Artisan Commands

The module provides several Artisan commands for managing proxies from the command line.

#### Check Proxies
Run a check on all available (free) proxies in the database.

```bash
php artisan proxy:check
```

You can also check a specific proxy by providing its address:

```bash
php artisan proxy:check --proxy=192.168.1.1:8080
```

#### Test Connectivity
Test the connectivity of a specific proxy configuration.

```bash
php artisan proxy:test <ip> <port> [protocol]
```

Example:
```bash
php artisan proxy:test 127.0.0.1 8080 https
```

### Programmatic Usage

You can use the `Juzaweb\Modules\Proxies\Contracts\ProxyManager` contract to interact with proxies in your code.

#### Retrieve a Random Active Proxy

To get a random proxy that is currently active:

```php
use Juzaweb\Modules\Proxies\Contracts\ProxyManager;

$proxyManager = app(ProxyManager::class);
$proxy = $proxyManager->random();

if ($proxy) {
    // Use the proxy
    echo $proxy->ip . ':' . $proxy->port;
}
```

#### Retrieve and Reserve an Available Proxy

To get an available ("free") proxy and mark it as in-use (setting `is_free` to `false`):

```php
use Juzaweb\Modules\Proxies\Contracts\ProxyManager;

$proxyManager = app(ProxyManager::class);
$proxy = $proxyManager->free();

if ($proxy) {
    // The proxy is now marked as not free.
    // Use the proxy...
}
```

### Helper Functions

The module provides global helper functions for working with proxy strings:

- `is_proxy_format(string $proxy): bool` - Validates if a string is in the format `IP:PORT`.
- `parse_proxy_string_to_array(string $proxy): array` - Parses a proxy string into an array with keys `ip`, `port`, `protocol`, `created_at`, and `updated_at`.

## License

This project is licensed under the GPL-2.0 License.
