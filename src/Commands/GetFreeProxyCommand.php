<?php

namespace Juzaweb\Proxies\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Juzaweb\Proxies\Contracts\Proxy;
use Juzaweb\Proxies\Models\Proxy as ProxyModel;

class GetFreeProxyCommand extends Command
{
    protected $name = 'proxy:free-list';

    protected $description = 'Get free proxy list.';

    public function handle(): int
    {
        $helper = app(Proxy::class);

        /*$client = new Client([
            'timeout' => 20,
            'proxy' => $helper->getProxyParamByProtocol('139.255.67.51', '3888', 'https'),
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)
        Chrome/58.0.3029.110 Safari/537.3',
            ]
        ]);

        dd($client->get('http://azenv.net')->getBody()->getContents());*/

        $proxies = $this->getGeoNodeProxies()['data'];
        foreach ($proxies as $proxy) {
            $this->info("Ping {$proxy['ip']}:{$proxy['port']}");

            foreach ($proxy['protocols'] as $protocol) {
                if ($helper->test($proxy['ip'], $proxy['port'], $protocol)) {
                    ProxyModel::create(
                        array_merge(
                            Arr::only($proxy, ['ip', 'port', 'country']),
                            [
                                'protocol' => $protocol,
                            ]
                        )
                    );

                    $this->info("=> OK {$protocol} {$proxy['ip']}:{$proxy['port']}");
                }
            }
        }

        return Command::SUCCESS;
    }

    protected function getGeoNodeProxies()
    {
        $url = 'https://proxylist.geonode.com/api/proxy-list?limit=50&page=1&sort_by=lastChecked&sort_type=desc&protocols=https';

        $client = new Client(
            [
                'timeout' => 20,
                'verify' => false,
            ]
        );

        return json_decode($client->get($url)->getBody()->getContents(), true);
    }
}
