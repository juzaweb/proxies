<?php

namespace Juzaweb\Proxies\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Juzaweb\Backend\Http\Controllers\Backend\PageController;
use Juzaweb\CMS\Traits\ResourceController;
use Juzaweb\Proxies\Http\Datatables\ProxyDatatable;
use Juzaweb\Proxies\Models\Proxy;
use Juzaweb\Proxies\Models\Proxy as ProxyModel;

class ProxyController extends PageController
{
    use ResourceController;

    protected string $viewPrefix = 'jwpr::backend.proxy';

    public function import(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate(['proxies' => ['required', 'string']]);

        $proxies = $request->input('proxies');

        $proxies = collect(explode("\n", $proxies))
            ->map(fn($proxy) => trim($proxy))
            ->filter(fn($proxy) => !empty($proxy) && is_proxy_format($proxy))
            ->map(fn($proxy) => parse_proxy_string_to_array($proxy))
            ->values();

        if ($proxies->isEmpty()) {
            return $this->error(['message' => __('Proxies format is invalid.')]);
        }

        $exists = ProxyModel::whereIn('ip', $proxies->pluck('ip')->toArray())
            ->get()
            ->keyBy('ip');
        $proxies = $proxies->filter(fn($proxy) => !isset($exists[$proxy['ip']]));

        Proxy::insert($proxies->toArray());

        return $this->success(['message' => __('Proxies imported.')]);
    }

    public function recheck(Request $request): JsonResponse|RedirectResponse
    {
        $total = Proxy::where(['is_free' => true])->count();

    }

    protected function parseDataForSave(array $attributes, ...$params): array
    {
        $attributes['active'] = !empty($attributes['active']) ? 1 : 0;
        return $attributes;
    }

    protected function getDataTable(...$params): ProxyDatatable
    {
        return new ProxyDatatable();
    }

    protected function validator(array $attributes, ...$params): array
    {
        return [
            'ip' => ['required'],
            'port' => ['required'],
            'protocol' => ['required', 'in:http,https'],
            'active' => ['nullable', 'in:1'],
        ];
    }

    protected function getModel(...$params): string
    {
        return Proxy::class;
    }

    protected function getTitle(...$params): string
    {
        return 'Proxies';
    }
}
