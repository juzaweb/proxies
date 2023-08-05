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
        $this->addAction(Action::INIT_ACTION, [$this, 'addConfigs']);
    }

    public function addConfigs(): void
    {
        $this->hookAction->addSettingForm(
            'proxies',
            [
                'name' => 'Proxies',
            ]
        );

        $this->hookAction->registerConfig(
            [
                'proxy_test_url' => [
                    'label' => 'Test proxy url',
                    'form' => 'proxies',
                    'data' => [
                        'default' => 'https://translate.google.com',
                    ]
                ],
                'proxy_test_timeout' => [
                    'label' => 'Test proxy timeout',
                    'form' => 'proxies',
                    'data' => [
                        'default' => 20,
                    ]
                ],
                'proxy_auto_test_enable' => [
                    'label' => 'Auto test proxy',
                    'form' => 'proxies',
                    'type' => 'select',
                    'data' => [
                        'options' => [
                            0 => 'Disabled',
                            1 => 'Enabled',
                        ]
                    ]
                ],
                'proxy_auto_craw_free_list_enable' => [
                    'label' => 'Auto crawl free list',
                    'form' => 'proxies',
                    'type' => 'select',
                    'data' => [
                        'options' => [
                            0 => 'Disabled',
                            1 => 'Enabled',
                        ]
                    ]
                ]
            ]
        );
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

        $this->hookAction->registerAdminAjax(
            'proxies.re-check',
            [
                'method' => 'POST',
                'callback' => [ProxyController::class, 'recheck'],
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
