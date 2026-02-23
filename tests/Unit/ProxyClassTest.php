<?php

namespace Juzaweb\Modules\Proxies\Tests\Unit;

use Juzaweb\Modules\Proxies\Support\Proxy;
use Juzaweb\Modules\Proxies\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class ProxyClassTest extends TestCase
{
    public function test_test_method_uses_correct_user_agent()
    {
        // Create a mock handler to intercept the request
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar']),
        ]);

        $container = [];
        $history = Middleware::history($container);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client(['handler' => $handlerStack]);

        // Instantiate Proxy.
        $proxy = new Proxy($this->app);

        // Use reflection to set the protected client property
        $reflection = new \ReflectionClass($proxy);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($proxy, $client);

        // Call the test method
        $proxy->test('127.0.0.1', 8080, 'http');

        // Verify the request
        $this->assertCount(1, $container);
        $transaction = $container[0];
        $request = $transaction['request'];

        $userAgent = $request->getHeaderLine('User-Agent');

        $expectedUserAgent = Proxy::DEFAULT_USER_AGENT;

        $this->assertStringContainsString('Mozilla/5.0', $userAgent);
        $this->assertEquals($expectedUserAgent, $userAgent);
    }
}
