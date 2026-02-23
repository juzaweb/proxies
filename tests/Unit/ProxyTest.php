<?php

namespace Juzaweb\Modules\Proxies\Tests\Unit;

use Juzaweb\Modules\Proxies\Models\Proxy;
use Juzaweb\Modules\Proxies\Tests\TestCase;

class ProxyTest extends TestCase
{
    public function test_proxies_table_exists()
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('jwpr_proxies'));
    }

    public function test_create_proxy()
    {
        $proxy = Proxy::create([
            'ip' => '127.0.0.1',
            'port' => '8080',
            'protocol' => 'http',
        ]);

        $this->assertDatabaseHas('jwpr_proxies', [
            'ip' => '127.0.0.1',
            'port' => '8080',
            'protocol' => 'http',
        ]);
    }

    public function test_proxy_manager_retrieve_proxy()
    {
        $proxy = Proxy::create([
            'ip' => '127.0.0.1',
            'port' => '8080',
            'protocol' => 'http',
            'active' => true,
            'is_free' => true,
        ]);

        $mockProxy = \Mockery::mock(\Juzaweb\Modules\Proxies\Contracts\Proxy::class);
        $mockProxy->shouldReceive('test')
            ->once()
            ->andReturn(true);

        $this->app->instance(\Juzaweb\Modules\Proxies\Contracts\Proxy::class, $mockProxy);

        // Manually create ProxyManager to ensure it uses the mocked Proxy contract
        $manager = new \Juzaweb\Modules\Proxies\Support\ProxyManager($this->app);

        $retrievedProxy = $manager->free('http');

        $this->assertNotNull($retrievedProxy);
        $this->assertEquals($proxy->id, $retrievedProxy->id);
    }
}
