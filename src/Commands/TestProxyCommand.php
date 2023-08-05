<?php

namespace Juzaweb\Proxies\Commands;

use Illuminate\Console\Command;
use Juzaweb\Proxies\Contracts\Proxy;
use Symfony\Component\Console\Input\InputArgument;

class TestProxyCommand extends Command
{
    protected $name = 'proxy:test';

    protected $description = 'Command description.';

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
            ['ip', InputArgument::REQUIRED, 'The ip proxy.'],
            ['port', InputArgument::REQUIRED, 'The ip proxy.'],
            ['protocol', InputArgument::OPTIONAL, 'The ip proxy.', 'https'],
        ];
    }
}
