<?php

namespace Juzaweb\Modules\Proxies\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Juzaweb\Modules\Proxies\Commands\GetFreeProxyCommand;
use Juzaweb\Modules\Proxies\Commands\ProxyCheckCommand;
use Juzaweb\Modules\Proxies\Commands\TestProxyCommand;
use Juzaweb\Modules\Proxies\Contracts\Proxy as ProxyContract;
use Juzaweb\Modules\Proxies\Contracts\ProxyManager as ProxyManagerContract;
use Juzaweb\Modules\Proxies\Support\Proxy;
use Juzaweb\Modules\Proxies\Support\ProxyManager;

class ProxiesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands(
            [
                GetFreeProxyCommand::class,
                TestProxyCommand::class,
                ProxyCheckCommand::class,
            ]
        );

        $this->app->booted(
            function () {
                $schedule = $this->app->make(Schedule::class);
                if (get_config('proxy_auto_test_enable') == 1) {
                    $schedule->command(ProxyCheckCommand::class)->hourly();
                }

                if (get_config('proxy_auto_craw_free_list_enable') == 1) {
                    $schedule->command(GetFreeProxyCommand::class)->hourly();
                }
            }
        );
    }

    public function register(): void
    {
        $this->app->singleton(
            ProxyContract::class,
            fn($app) => new Proxy($app)
        );

        $this->app->singleton(
            ProxyManagerContract::class,
            fn($app) => new ProxyManager($app)
        );

        $this->mergeConfigFrom(__DIR__ . '/../../config/proxy.php', 'proxy');
    }
}
