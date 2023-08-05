<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Proxies\Commands;

use Illuminate\Console\Command;
use Juzaweb\Proxies\Contracts\ProxyManager;
use Juzaweb\Proxies\Models\Proxy;

class ProxyCheckCommand extends Command
{
    protected $signature = 'proxy:check';

    protected $description = 'Command check proxies.';

    public function handle(): int
    {
        $proxies = Proxy::where(['is_free' => true])->get();

        foreach ($proxies as $proxy) {
            if (app(ProxyManager::class)->testOrDisable($proxy)) {
                if (!$proxy->active) {
                    $proxy->update(['active' => true]);
                }
                $this->info("=> OK {$proxy->id}");
            } else {
                $this->error("=> Error {$proxy->id}");
            }
        }

        return Command::SUCCESS;
    }
}
