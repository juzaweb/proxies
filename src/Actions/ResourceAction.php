<?php

namespace Juzaweb\Proxies\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\Proxies\Http\Controllers\ProxyController;
use Juzaweb\Proxies\Http\Datatables\ProxyDatatable;
use Juzaweb\Proxies\Repositories\ProxyRepository;

class ResourceAction extends Action
{
    public function handle(): void
    {
        //$this->addAction(Action::INIT_ACTION, [$this, 'addResources']);
        $this->addAction(Action::BACKEND_INIT, [$this, 'addMenu']);
        $this->addAction(Action::BACKEND_INIT, [$this, 'addAdminAjax']);
    }

    public function addAdminAjax(): void
    {
        $this->hookAction->registerAdminAjax(
            'proxies.import',
            [
                'method' => 'POST',
                'callback' => [ProxyController::class, 'import'],
            ]
        );
    }

    public function addMenu(): void
    {
        $this->hookAction->registerAdminPage(
            'proxies',
            [
                'title' => 'Proxies',
                'menu' => [
                    'parent' => 'managements',
                ],
            ]
        );
    }

    public function addResources(): void
    {
        $this->hookAction->registerResource(
            'proxies',
            null,
            [
                'label' => 'Proxies',
                'repository' => ProxyRepository::class,
                'datatable' => ProxyDatatable::class,
                'menu' => [
                    'parent' => 'managements',
                ],
                'validator' => [
                    'ip' => ['required'],
                    'port' => ['required'],
                    'protocol' => ['required', 'in:http,https'],
                    'active' => ['required', 'in:0,1'],
                ],
                'fields' => [
                    'ip' => [
                        'type' => 'text',
                        'label' => trans('jwpr::content.ip'),
                    ],
                    'port' => [
                        'type' => 'text',
                        'label' => trans('jwpr::content.port'),
                        'sidebar' => true,
                    ],
                    'protocol' => [
                        'type' => 'text',
                        'label' => trans('jwpr::content.protocol'),
                        'sidebar' => true,
                    ],
                    'country' => [
                        'type' => 'text',
                    ],
                    'active' => [
                        'type' => 'select',
                        'sidebar' => true,
                        'data' => [
                            'options' => [
                                0 => trans('cms::app.disabled'),
                                1 => trans('cms::app.enabled'),
                            ]
                        ]
                    ],
                ],
            ]
        );
    }
}
