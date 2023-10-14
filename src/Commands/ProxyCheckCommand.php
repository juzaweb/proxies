<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Proxies\Commands;

use Illuminate\Console\Command;
use Juzaweb\Proxies\Contracts\Proxy as ProxyContract;
use Juzaweb\Proxies\Contracts\ProxyManager;
use Juzaweb\Proxies\Models\Proxy;
use Symfony\Component\Console\Input\InputOption;

class ProxyCheckCommand extends Command
{
    protected $name = 'proxy:check';

    protected $description = 'Check proxies command.';

    public function handle(): int
    {
        if ($proxy = $this->option('proxy')) {
            // Proxy format example: 127.0.0.1:8080
            $proxy = explode(':', $proxy);

            $test = app(ProxyContract::class)->test(
                $proxy[0],
                $proxy[1],
                'http',
                ['throwable' => $this->option('throwable')]
            );

            if ($test) {
                $this->info("=> OK {$proxy[0]}:{$proxy[1]}");
            } else {
                $this->error("=> Error {$proxy[0]}:{$proxy[1]}");
            }
        } else {
            $this->testAllProxies();
        }

        return 0;
    }

    public function testAllProxies(): void
    {
        $proxies = Proxy::where(['is_free' => true])->get();

        foreach ($proxies as $proxy) {
            if (app(ProxyManager::class)->testOrDisable($proxy, ['throwable' => $this->option('throwable')])) {
                if (!$proxy->active) {
                    $proxy->update(['active' => true]);
                }
                $this->info("=> OK {$proxy->id}");
            } else {
                $this->error("=> Error {$proxy->id}");
            }
        }
    }

    protected function getOptions(): array
    {
        return [
            ['proxy', null, InputOption::VALUE_OPTIONAL, 'Proxy for test, if not set, all proxies.'],
            ['throwable', null, InputOption::VALUE_NEGATABLE, 'Throw exception if test failed.', false],
        ];
    }
}
