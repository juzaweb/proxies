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

/**
 * @see \Juzaweb\Proxies\Support\Proxy
 */
interface Proxy
{
    /**
     * Test the connection to a given IP and port using a specific protocol.
     *
     * @param string $ip The IP address to test.
     * @param int $port The port number to test.
     * @param string $protocol The protocol to use for the connection.
     * @param array $options Additional options for the connection (optional).
     * @throws \Throwable If an error occurs during the connection attempt.
     * @return bool Returns true if the connection is successful, false otherwise.
     */
    public function test(string $ip, int $port, string $protocol, array $options = []): bool;

    /**
     * Retrieves the proxy parameter based on the given IP, port, and protocol.
     *
     * @param string $ip The IP address of the proxy.
     * @param int $port The port of the proxy.
     * @param string $protocol The protocol of the proxy.
     * @param array $options An array of additional options (default: []).
     * @throws \Exception If the protocol is not supported.
     * @return string|array The proxy parameter.
     */
    public function getProxyParamByProtocol(string $ip, int $port, string $protocol, array $options = []): string|array;
}
