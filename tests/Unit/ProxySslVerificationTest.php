<?php

namespace Juzaweb\Modules\Proxies\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Juzaweb\Modules\Proxies\Support\Proxy;
use Juzaweb\Modules\Proxies\Tests\TestCase;
use Mockery;

class ProxySslVerificationTest extends TestCase
{
    public function test_proxy_test_uses_ssl_verification()
    {
        $ip = '127.0.0.1';
        $port = 8080;
        $protocol = 'http';

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')
            ->once()
            ->andReturn(new Response(200));

        /** @var Proxy|Mockery\MockInterface $proxy */
        $proxy = Mockery::mock(Proxy::class, [$this->app])->makePartial();
        $proxy->shouldAllowMockingProtectedMethods();

        $proxy->shouldReceive('createClient')
            ->once()
            ->with(Mockery::on(function ($config) {
                return isset($config['verify']) && $config['verify'] === true;
            }))
            ->andReturn($mockClient);

        $result = $proxy->test($ip, $port, $protocol);

        $this->assertTrue($result);
    }
}
