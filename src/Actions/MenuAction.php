<?php

namespace Juzaweb\Modules\Proxies\Actions;

use Juzaweb\Modules\Core\Facades\Menu;

class MenuAction
{
    public function handle(): void
    {
        Menu::make(
            'proxies',
            function () {
                return [
                    'title' => 'Proxies',
                    'url' => 'proxies',
                    'icon' => 'fa fa-server',
                    'priority' => 20,
                    'parent' => null,
                ];
            }
        );
    }
}
