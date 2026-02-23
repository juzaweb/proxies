<?php

namespace Juzaweb\Modules\Proxies\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Proxies\Http\Requests\ProxyRequest;
use Juzaweb\Modules\Proxies\Http\Datatables\ProxyDatatable;
use Juzaweb\Modules\Proxies\Models\Proxy;

class ProxyController extends AdminController
{
    public function index(ProxyDatatable $dataTable)
    {
        Breadcrumb::add(trans('core::app.dashboard'), admin_url());
        Breadcrumb::add('Proxies', route('admin.proxies.index'));

        return $dataTable->render('proxies::backend.proxy.index', [
            'title' => 'Proxies',
        ]);
    }

    public function create()
    {
        Breadcrumb::add(trans('core::app.dashboard'), admin_url());
        Breadcrumb::add('Proxies', route('admin.proxies.index'));
        Breadcrumb::add(trans('core::app.add_new'), route('admin.proxies.create'));

        return view('proxies::backend.proxy.form', [
            'title' => 'Create Proxy',
            'model' => new Proxy(),
        ]);
    }

    public function store(ProxyRequest $request)
    {
        Proxy::create($request->all());

        return $this->success([
            'message' => trans('core::app.created_successfully'),
            'redirect' => route('admin.proxies.index'),
        ]);
    }

    public function edit($id)
    {
        $model = Proxy::findOrFail($id);

        Breadcrumb::add(trans('core::app.dashboard'), admin_url());
        Breadcrumb::add('Proxies', route('admin.proxies.index'));
        Breadcrumb::add(trans('core::app.edit'), route('admin.proxies.edit', $id));

        return view('proxies::backend.proxy.form', [
            'title' => 'Edit Proxy',
            'model' => $model,
        ]);
    }

    public function update(ProxyRequest $request, $id)
    {
        $model = Proxy::findOrFail($id);
        $model->update($request->all());

        return $this->success([
            'message' => trans('core::app.updated_successfully'),
            'redirect' => route('admin.proxies.index'),
        ]);
    }

    public function destroy($id)
    {
        $model = Proxy::findOrFail($id);
        $model->delete();

        return $this->success([
            'message' => trans('core::app.deleted_successfully'),
        ]);
    }
}
