<?php

namespace Juzaweb\Proxies\Models;

use Juzaweb\CMS\Models\Model;
use Juzaweb\Proxies\Contracts\Proxy as ProxyContract;

class Proxy extends Model
{
    protected $table = 'jwpr_proxies';

    protected string $fieldName = 'ip';

    protected $fillable = [
        'ip',
        'port',
        'protocol',
        'country',
        'is_free',
        'active',
        'username',
        'password',
    ];

    public function toGuzzleHttpProxy(): string|array
    {
        return app(ProxyContract::class)->getProxyParamByProtocol(
            $this->ip,
            $this->port,
            $this->protocol,
            ['username' => $this->username, 'password' => $this->password]
        );
    }
}
