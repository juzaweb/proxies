<?php

namespace Juzaweb\Modules\Proxies\Tests\Unit;

use Juzaweb\Modules\Proxies\Commands\TestProxyCommand;
use Juzaweb\Modules\Proxies\Tests\TestCase;
use Symfony\Component\Console\Input\InputArgument;

class TestProxyCommandTest extends TestCase
{
    public function test_command_has_correct_description()
    {
        $command = new TestProxyCommand();
        $this->assertEquals('Test a proxy connection.', $command->getDescription());
    }

    public function test_command_has_correct_arguments_description()
    {
        $command = new TestProxyCommand();
        $definition = $command->getDefinition();

        $ipArg = $definition->getArgument('ip');
        $this->assertEquals('The proxy IP address.', $ipArg->getDescription());

        $portArg = $definition->getArgument('port');
        $this->assertEquals('The proxy port.', $portArg->getDescription());

        $protocolArg = $definition->getArgument('protocol');
        $this->assertEquals('The proxy protocol (http, https, socks4, socks5).', $protocolArg->getDescription());
        $this->assertEquals('https', $protocolArg->getDefault());
    }
}
