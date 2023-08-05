<?php

namespace Juzaweb\Proxies\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Proxies\Actions\ResourceAction;
use Juzaweb\Proxies\Commands\GetFreeProxyCommand;
use Juzaweb\Proxies\Commands\ProxyCheckCommand;
use Juzaweb\Proxies\Commands\TestProxyCommand;
use Juzaweb\Proxies\Contracts\Proxy as ProxyContract;
use Juzaweb\Proxies\Contracts\ProxyManager as ProxyManagerContract;
use Juzaweb\Proxies\Repositories\ProxyRepository;
use Juzaweb\Proxies\Repositories\ProxyRepositoryEloquent;
use Juzaweb\Proxies\Support\Proxy;
use Juzaweb\Proxies\Support\ProxyManager;

class ProxiesServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ProxyRepository::class => ProxyRepositoryEloquent::class,
    ];

    public function boot()
    {
        $this->commands(
            [
                GetFreeProxyCommand::class,
                TestProxyCommand::class,
                ProxyCheckCommand::class,
            ]
        );

        ActionRegister::register([ResourceAction::class]);

        $this->app->booted(
            function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command(ProxyCheckCommand::class)->hourly();
            }
        );
    }

    public function register()
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
