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
    /**
     * Retrieves a free proxy with the given protocol.
     *
     * @param string $protocol The protocol of the proxy (default: 'https')
     * @return \Proxy|null The retrieved proxy or null if no free proxy is available
     */
    public function free(string $protocol = 'https'): ?Proxy;

    /**
     * Retrieves a random proxy with the specified protocol.
     *
     * @param string $protocol The protocol of the proxy (default: 'https')
     * @throws \Throwable Exception that occurred during the retrieval process
     * @return \Proxy|null The randomly selected proxy, or null if none are available
     */
    public function random(string $protocol = 'https'): ?Proxy;

    /**
     * Test a proxy is action or fail.
     *
     * @param Proxy $proxy The Proxy object to be tested.
     * @return bool The boolean result of the test.
     */
    public function test(Proxy $proxy): bool;

    public function testOrDisable(Proxy $proxy): bool;
}
