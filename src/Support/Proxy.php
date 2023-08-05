<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Proxies\Support;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Juzaweb\Proxies\Contracts\Proxy as ProxyContract;

class Proxy implements ProxyContract
{
    public function __construct(protected $app)
    {
    }

    public function test(string $ip, int $port, string $protocol, array $options = []): bool
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) ';
        $userAgent .= 'Chrome/58.0.3029.110 Safari/537.3';

        try {
            $client = new Client(
                [
                    'timeout' => $options['timeout'] ?? $this->app['config']->get('proxy.tests.timeout'),
                    'proxy' => $this->getProxyParamByProtocol($ip, $port, $protocol, $options),
                    'verify' => false,
                    'headers' => [
                        'User-Agent' => $userAgent,
                    ]
                ]
            );

            $response = $client->get($this->app['config']->get('proxy.tests.site_url'));

            if ($response->getStatusCode() == 200) {
                return true;
            }
        } catch (\Throwable $e) {
            throw_if(Arr::get($options, 'throwable'), $e);
        }

        return false;
    }

    public function getProxyParamByProtocol(string $ip, int $port, string $protocol, array $options = []): string|array
    {
        if (in_array($protocol, ['http', 'https'])) {
            $username = Arr::get($options, 'username');
            $password = Arr::get($options, 'password');
            $prefix = $username && $password ? "{$username}:{$password}@" : '';
            return "http://{$prefix}{$ip}:{$port}";
        }

        if ($protocol == 'socks4') {
            return "socks4://{$ip}:{$port}";
        }

        if ($protocol == 'socks5') {
            return "socks5://{$ip}:{$port}";
        }

        throw new \Exception("Protocol {$protocol} not supported.");
    }
}
