<?php

namespace Juzaweb\Modules\Proxies\Http\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Proxies\Models\Proxy;

class ProxyDatatable extends DataTable
{
    /**
     * Columns definition
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::make('ip', 'ip')->title('IP'),
            Column::make('port', 'port')->title('Port'),
            Column::make('protocol', 'protocol')->title('Protocol'),
            Column::make('country', 'country')->title('Country'),
            Column::make('active', 'active')->title('Status'),
            Column::make('created_at', 'created_at')->title(trans('core::app.created_at')),
            Column::actions(),
        ];
    }

    /**
     * Query data
     *
     * @param Builder $query
     * @return Builder
     */
    public function query($query)
    {
        $query = Proxy::query();

        return $query;
    }

    public function dataTable($query)
    {
        $dataTable = parent::dataTable($query);

        $dataTable->editColumn('active', function ($row) {
            return $row->active == 1
                ? '<span class="text-success">Active</span>'
                : '<span class="text-danger">Inactive</span>';
        });

        $dataTable->editColumn('protocol', function ($row) {
            return strtoupper($row->protocol);
        });

        return $dataTable;
    }

    public function bulkActions(): array
    {
        return [
            'delete' => BulkAction::delete(),
        ];
    }
}
