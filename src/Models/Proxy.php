<?php

namespace Juzaweb\Proxies\Models;

use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\ResourceModel;

class Proxy extends Model
{
    use ResourceModel;

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
        return app(\Juzaweb\Proxies\Contracts\Proxy::class)->getProxyParamByProtocol(
            $this->ip,
            $this->port,
            $this->protocol,
            ['username' => $this->username, 'password' => $this->password]
        );
    }
}
