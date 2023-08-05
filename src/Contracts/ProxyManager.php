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

use Juzaweb\Proxies\Models\Proxy;

/**
 * @see \Juzaweb\Proxies\Support\ProxyManager
 */
interface ProxyManager
{
    public function free(string $protocol = 'https'): ?Proxy;

    public function random(string $protocol = 'https'): ?Proxy;

    public function test(Proxy $proxy);

    public function testOrDisable(Proxy $proxy): bool;
}
