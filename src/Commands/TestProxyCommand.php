<?php

namespace Juzaweb\Modules\Proxies\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Proxies\Contracts\Proxy;
use Symfony\Component\Console\Input\InputArgument;

class TestProxyCommand extends Command
{
    protected $name = 'proxy:test';

    protected $description = 'Test a proxy connection.';

    public function handle()
    {
        $helper = app(Proxy::class);

        $result = $helper->test(
            $this->argument('ip'),
            $this->argument('port'),
            $this->argument('protocol')
        );

        if ($result) {
            $this->info('OK');
        } else {
            $this->error('ERROR');
        }
    }

    protected function getArguments(): array
    {
        return [
            ['ip', InputArgument::REQUIRED, 'The proxy IP address.'],
            ['port', InputArgument::REQUIRED, 'The proxy port.'],
            ['protocol', InputArgument::OPTIONAL, 'The proxy protocol (http, https, socks4, socks5).', 'https'],
        ];
    }
}
