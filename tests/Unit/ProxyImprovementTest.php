<?php

namespace Juzaweb\Modules\Proxies\Tests\Unit;

use Juzaweb\Modules\Proxies\Support\Proxy;
use Juzaweb\Modules\Proxies\Support\ProxyManager;
use Juzaweb\Modules\Proxies\Tests\TestCase;
use Juzaweb\Modules\Proxies\Models\Proxy as ProxyModel;
use Illuminate\Support\Facades\DB;
use Mockery;

class ProxyImprovementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Ensure table is clean
        ProxyModel::truncate();
    }

    public function test_get_proxy_param_by_protocol_socks5_auth()
    {
        $proxy = new Proxy($this->app);

        // Test with simple username and password
        $result = $proxy->getProxyParamByProtocol('127.0.0.1', 1080, 'socks5', [
            'username' => 'user',
            'password' => 'pass'
        ]);

        $this->assertEquals('socks5://user:pass@127.0.0.1:1080', $result);

        // Test with special chars
        $result = $proxy->getProxyParamByProtocol('127.0.0.1', 1080, 'socks5', [
            'username' => 'user@name',
            'password' => 'pass:word'
        ]);

        $this->assertEquals('socks5://user%40name:pass%3Aword@127.0.0.1:1080', $result);

        // Test without username and password
        $resultNoAuth = $proxy->getProxyParamByProtocol('127.0.0.1', 1080, 'socks5');
        $this->assertEquals('socks5://127.0.0.1:1080', $resultNoAuth);
    }

    public function test_get_proxy_param_by_protocol_http_auth()
    {
        $proxy = new Proxy($this->app);

        // Test with special chars
        $result = $proxy->getProxyParamByProtocol('127.0.0.1', 8080, 'http', [
            'username' => 'user@name',
            'password' => 'pass:word'
        ]);

        $this->assertEquals('http://user%40name:pass%3Aword@127.0.0.1:8080', $result);

        // Test without auth
        $result = $proxy->getProxyParamByProtocol('127.0.0.1', 8080, 'http');
        $this->assertEquals('http://127.0.0.1:8080', $result);
    }

    public function test_proxy_manager_retry_limit()
    {
        // Mock Proxy contract to always fail the test
        $mockProxy = Mockery::mock(\Juzaweb\Modules\Proxies\Contracts\Proxy::class);
        $mockProxy->shouldReceive('test')
            ->andReturn(false); // Always fail

        $this->app->instance(\Juzaweb\Modules\Proxies\Contracts\Proxy::class, $mockProxy);

        // Create 20 active proxies
        // If MAX_RETRIES is 10, we expect 10 to be disabled and 10 to remain active.
        for ($i = 0; $i < 20; $i++) {
            ProxyModel::create([
                'ip' => "10.0.0.{$i}",
                'port' => 80,
                'protocol' => 'http',
                'active' => true,
                'is_free' => true,
            ]);
        }

        $manager = new ProxyManager($this->app);

        $result = $manager->free('http');

        $this->assertNull($result);

        // Count active proxies
        $activeCount = ProxyModel::where('active', true)->count();

        // Assert that we still have some active proxies (meaning we gave up)
        $this->assertGreaterThan(0, $activeCount, "Should abort after max retries, leaving some proxies active.");
        $this->assertEquals(10, $activeCount, "Should disable exactly MAX_RETRIES proxies.");
    }
}
