<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Proxies\Support;

use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Proxies\Contracts\Proxy as ProxyContract;
use Juzaweb\Modules\Proxies\Models\Proxy;

class ProxyManager implements \Juzaweb\Modules\Proxies\Contracts\ProxyManager
{
    protected ProxyContract $proxy;

    public function __construct(protected $app)
    {
        $this->proxy = $this->app[ProxyContract::class];
    }

    public function free(string $protocol = 'http'): ?Proxy
    {
        $i = 0;
        while ($i < 10) {
            $i++;
            DB::beginTransaction();
            try {
                $proxy = Proxy::where(['protocol' => $protocol, 'is_free' => true, 'active' => true])
                    ->lockForUpdate()
                    ->inRandomOrder()
                    ->first();

                if (empty($proxy)) {
                    DB::commit();
                    return null;
                }

                $result = $this->testOrDisable($proxy);

                if ($result) {
                    $proxy->update(['is_free' => false]);
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }

            if ($result) {
                return $proxy;
            }
        }

        return null;
    }

    public function random(string $protocol = 'http'): ?Proxy
    {
        $i = 0;
        while ($i < 10) {
            $i++;
            DB::beginTransaction();
            try {
                $proxy = Proxy::where(['protocol' => $protocol, 'active' => true])
                    ->inRandomOrder()
                    ->lockForUpdate()
                    ->first();

                if (empty($proxy)) {
                    DB::commit();
                    return null;
                }

                $result = $this->testOrDisable($proxy);

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }

            if ($result) {
                return $proxy;
            }
        }

        return null;
    }

    public function test(Proxy $proxy, array $options = []): bool
    {
        $options = array_merge(['username' => $proxy->username, 'password' => $proxy->password], $options);

        return $this->proxy->test(
            $proxy->ip,
            $proxy->port,
            $proxy->protocol,
            $options
        );
    }

    public function testOrDisable(Proxy $proxy, array $options = []): bool
    {
        if (!$this->test($proxy, $options)) {
            $proxy->update(['active' => false]);

            return false;
        }

        return true;
    }
}
