# Copilot Instructions for Proxies Module

## Project Overview
This is a JuzaWeb module for crawling and managing HTTP/HTTPS/SOCKS proxies. It provides automated proxy testing, pool management, and concurrent allocation with database locking.

## Architecture Patterns

### Contract-First Design
- Always implement interfaces from [src/Contracts/](src/Contracts/) before concrete classes
- Proxy functionality split into `Proxy` (testing/connectivity) and `ProxyManager` (pool management)
- Service binding in [src/Providers/ProxiesServiceProvider.php](src/Providers/ProxiesServiceProvider.php) using dependency injection

### Database Concurrency Pattern
The ProxyManager uses `lockForUpdate()` for thread-safe proxy allocation:
```php
$proxy = Proxy::where(['protocol' => $protocol, 'is_free' => true, 'active' => true])
    ->lockForUpdate()
    ->inRandomOrder()
    ->first();
```
Always wrap proxy allocation in database transactions.

### Settings Integration
Uses JuzaWeb's `setting()` helper for configuration:
- `proxy_test_url` - Target URL for connectivity tests (default: translate.google.com)
- `proxy_test_timeout` - Test timeout in seconds (default: 20)
- `proxy_auto_test_enable` - Enables hourly automated testing

## Key Components

### Proxy Testing ([src/Support/Proxy.php](src/Support/Proxy.php))
- Uses Guzzle HTTP client with proper User-Agent simulation
- Returns boolean for connectivity, throws exceptions only when `throwable` option is true
- Protocol formats: `http://user:pass@ip:port`, `socks4://ip:port`, `socks5://ip:port`

### Database Schema ([database/migrations/](database/migrations/))
- Table: `jwpr_proxies` with unique constraint on `(ip, port, protocol)`
- Fields: `is_free` (allocation state), `active` (health status), optional auth credentials
- Use factory pattern for test data creation

### Commands ([src/Commands/](src/Commands/))
- `proxy:check` - Test single proxy or all proxies in database
- `--proxy` option format: `127.0.0.1:8080`
- Registers automatically for scheduling when `proxy_auto_test_enable` is enabled

## Development Workflows

### Adding New Protocol Support
1. Extend `getProxyParamByProtocol()` in [src/Support/Proxy.php](src/Support/Proxy.php)
2. Add protocol to migration enum if constrained
3. Update model's `toGuzzleHttpProxy()` method
4. Add test cases to [tests/Unit/ProxyTest.php](tests/Unit/ProxyTest.php)

### Testing Strategy
- Mock the `ProxyContract` interface for unit tests
- Use `\Mockery::mock()` for proxy testing simulations
- Test database operations require `assertDatabaseHas()`
- Use TestCase base class from [tests/TestCase.php](tests/TestCase.php)

### Helper Functions ([src/helpers.php](src/helpers.php))
- `is_proxy_format()` - Validates IP:PORT string format
- `parse_proxy_string_to_array()` - Converts string to database array
- Always use helpers for proxy string validation before database operations

### Service Provider Registration
- Commands register in `boot()` method
- Service bindings use singletons in `register()` method
- Schedule tasks in `app->booted()` callback to ensure full Laravel bootstrap
- Config merging: `mergeConfigFrom(__DIR__ . '/../../config/proxy.php', 'proxy')`

## Performance Considerations
- Use `inRandomOrder()` for even proxy distribution
- Implement database locking for concurrent proxy allocation
- Consider proxy testing timeouts to prevent hanging operations
- Update proxy `active` status based on test results for health management