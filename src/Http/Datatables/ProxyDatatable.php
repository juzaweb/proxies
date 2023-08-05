<?php

namespace Juzaweb\Proxies\Http\Datatables;

use Juzaweb\Backend\Http\Datatables\ResourceDatatable;
use Juzaweb\Proxies\Models\Proxy;

class ProxyDatatable extends ResourceDatatable
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
                'formatter' => fn($value, $row, $index) => $value == 1 ? 'Yes': 'No',
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

    public function bulkActions($action, $ids)
    {
        switch ($action) {
            case 'delete':
                Proxy::destroy($ids);
                break;
        }
    }
}
