<?php

namespace Juzaweb\Proxies\Http\Datatables;

use Illuminate\Contracts\Database\Query\Builder;
use Juzaweb\CMS\Abstracts\DataTable;
use Juzaweb\CMS\Repositories\Criterias\FilterCriteria;
use Juzaweb\CMS\Repositories\Criterias\SearchCriteria;
use Juzaweb\Proxies\Models\Proxy;
use Juzaweb\Proxies\Repositories\ProxyRepository;

class ProxyDatatable extends DataTable
{
    /**
     * Columns datatable
     *
     * @return array
     */
    public function columns(): array
    {
        return [
            'ip' => [
                'label' => trans('jwpr::content.ip'),
                'formatter' => [$this, 'rowActionsFormatter'],
            ],
            'port' => [
                'label' => trans('jwpr::content.port'),
            ],
            'protocol' => [
                'label' => trans('jwpr::content.protocol'),
            ],
            'active' => [
                'label' => trans('jwpr::content.active'),
                'formatter' => fn($value, $row, $index) => $value == 1
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Inactive</span>',
            ],
            'is_free' => [
                'label' => trans('jwpr::content.is_free'),
                'formatter' => fn($value, $row, $index) => $value == 1 ? 'Yes' : 'No',
            ],
            'country' => [
                'label' => trans('jwpr::content.country'),
            ],
            'updated_at' => [
                'label' => trans('cms::app.updated_at'),
                'width' => '15%',
                'align' => 'center',
                'formatter' => function ($value, $row, $index) {
                    return jw_date_format($row->updated_at);
                }
            ],
            'created_at' => [
                'label' => trans('cms::app.created_at'),
                'width' => '15%',
                'align' => 'center',
                'formatter' => function ($value, $row, $index) {
                    return jw_date_format($row->created_at);
                }
            ]
        ];
    }

    public function searchFields(): array
    {
        $fields = parent::searchFields();

        $fields['is_free'] = [
            'label' => trans('jwpr::content.is_free'),
            'type' => 'select',
            'options' => [
                1 => 'Yes',
                0 => 'No',
            ],
        ];

        $fields['active'] = [
            'label' => trans('jwpr::content.active'),
            'type' => 'select',
            'options' => [
                1 => 'Active',
                0 => 'Inactive',
            ]
        ];

        return $fields;
    }

    public function bulkActions($action, $ids): void
    {
        switch ($action) {
            case 'delete':
                Proxy::destroy($ids);
                break;
        }
    }

    public function query(array $data): Builder
    {
        return app()->make(ProxyRepository::class)
            ->pushCriteria(new FilterCriteria($data))
            ->pushCriteria(new SearchCriteria($data))
            ->getQuery();
    }
}
