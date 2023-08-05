<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Proxies\Contracts;

interface Proxy
{
    public function test(string $ip, int $port, string $protocol, array $options = []): bool;

    public function getProxyParamByProtocol(string $ip, int $port, string $protocol, array $options = []): string|array;
}
