<?php

namespace Juzaweb\Modules\Proxies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProxyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ip' => 'required|string|max:150',
            'port' => 'required|string|max:10',
            'protocol' => 'required|string|max:20',
        ];
    }
}
