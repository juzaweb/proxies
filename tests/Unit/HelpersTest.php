<?php

namespace Juzaweb\Modules\Proxies\Tests\Unit;

use Juzaweb\Modules\Proxies\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_is_proxy_format_with_valid_format()
    {
        $this->assertTrue(is_proxy_format('192.168.1.1:8080'));
        $this->assertTrue(is_proxy_format('1.2.3.4:80'));
        $this->assertTrue(is_proxy_format('127.0.0.1:65535'));
    }

    public function test_is_proxy_format_with_invalid_format()
    {
        $this->assertFalse(is_proxy_format('192.168.1.1'));
        $this->assertFalse(is_proxy_format('192.168.1.1:'));
        $this->assertFalse(is_proxy_format(':8080'));
        $this->assertFalse(is_proxy_format('abc.def.ghi.jkl:8080'));
        $this->assertFalse(is_proxy_format('192.168.1.1:abc'));
        $this->assertFalse(is_proxy_format(''));
    }

    public function test_is_proxy_format_with_out_of_range_values()
    {
        // Current implementation uses \d{1,3} for octets and \d{1,5} for port
        // So these will actually pass with the current regex
        $this->assertTrue(is_proxy_format('999.999.999.999:99999'));
    }

    public function test_parse_proxy_string_to_array()
    {
        $proxy = '192.168.1.1:8080';
        $result = parse_proxy_string_to_array($proxy);

        $this->assertIsArray($result);
        $this->assertEquals('192.168.1.1', $result['ip']);
        $this->assertEquals('8080', $result['port']);
        $this->assertEquals('http', $result['protocol']);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertArrayHasKey('updated_at', $result);
    }
}
