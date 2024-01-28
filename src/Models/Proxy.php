<?php

namespace Juzaweb\Proxies\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\ResourceModel;
use Juzaweb\Proxies\Contracts\Proxy as ProxyContract;

/**
 * Juzaweb\Proxies\Models\Proxy
 *
 * @property int $id
 * @property string $ip
 * @property string $port
 * @property string $protocol
 * @property string|null $country
 * @property int $is_free
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $username
 * @property string|null $password
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy newModelQuery()
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy newQuery()
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy query()
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereActive($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereCountry($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereCreatedAt($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereFilter($params = [])
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereId($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereIp($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereIsFree($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy wherePassword($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy wherePort($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereProtocol($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereUpdatedAt($value)
 * @method static Builder|\Juzaweb\Proxies\Models\Proxy whereUsername($value)
 * @mixin \Eloquent
 */
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
        return app(ProxyContract::class)->getProxyParamByProtocol(
            $this->ip,
            $this->port,
            $this->protocol,
            ['username' => $this->username, 'password' => $this->password]
        );
    }
}
