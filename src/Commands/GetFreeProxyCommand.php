<?php

namespace Juzaweb\Modules\Proxies\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Modules\Proxies\Contracts\Proxy;
use Juzaweb\Modules\Proxies\Models\Proxy as ProxyModel;

class GetFreeProxyCommand extends Command
{
    protected $name = 'proxy:free-list';

    protected $description = 'Get free proxy list.';

    public function handle(): int
    {
        $helper = app(Proxy::class);
        $proxies = $this->getFreeProxies();

        $exists = ProxyModel::whereIn('ip', $proxies->pluck('ip')->toArray())
            ->get()
            ->keyBy('ip');

        $proxies = $proxies->filter(fn($proxy) => !isset($exists[$proxy['ip']]));

        foreach ($proxies as $proxy) {
            $this->info("Ping {$proxy['ip']}:{$proxy['port']}");

            if ($helper->test($proxy['ip'], $proxy['port'], 'http')) {
                ProxyModel::create(
                    array_merge(
                        Arr::only($proxy, ['ip', 'port', 'country']),
                        [
                            'protocol' => 'http',
                        ]
                    )
                );

                $this->info("=> OK insert {$proxy['ip']}:{$proxy['port']}");
            }
        }

        return 0;
    }

    protected function getFreeProxies(): Collection
    {
        $url = 'https://free-proxy-list.net';

        $client = new Client(
            [
                'timeout' => 20,
                'verify' => false,
            ]
        );

        $contents = $client->get($url)->getBody()->getContents();

        $html = str_get_html($contents);

        $proxies = $html->find('textarea[readonly="readonly"]', 0)?->innertext();

        return collect(explode("\n", $proxies))
            ->map(fn($proxy) => trim($proxy))
            ->filter(fn($proxy) => !empty($proxy) && $this->isProxyFormat($proxy))
            ->map(fn($proxy) => $this->parseProxyStringToArray($proxy))
            ->values();
    }

    protected function parseProxyStringToArray(string $proxy): array
    {
        $split = explode(':', $proxy);

        return [
            'ip' => $split[0],
            'port' => $split[1],
        ];
    }

    protected function isProxyFormat(string $proxy): bool
    {
        if (preg_match('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\:(\d{1,5})$/', $proxy)) {
            return true;
        }

        return false;
    }
}
