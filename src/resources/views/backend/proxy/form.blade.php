@extends('core::layouts.admin')

@section('content')
    @component('core::components.form', [
        'action' => $model->id ? route('proxies.update', $model->id) : route('proxies.store'),
        'method' => $model->id ? 'PUT' : 'POST',
    ])
        <div class="row">
            <div class="col-md-8">
                @component('core::components.card')
                    <div class="row">
                        <div class="col-md-6">
                            {{ Field::text(trans('IP Address'), 'ip', [
                                'value' => $model->ip,
                                'required' => true,
                            ]) }}
                        </div>

                        <div class="col-md-6">
                            {{ Field::text(trans('Port'), 'port', [
                                'value' => $model->port,
                                'required' => true,
                            ]) }}
                        </div>

                        <div class="col-md-6">
                            {{ Field::select(trans('Protocol'), 'protocol', [
                                'options' => [
                                    'http' => 'HTTP',
                                    'https' => 'HTTPS',
                                    'socks4' => 'SOCKS4',
                                    'socks5' => 'SOCKS5',
                                ],
                                'value' => $model->protocol,
                                'required' => true,
                            ]) }}
                        </div>

                        <div class="col-md-6">
                            {{ Field::text(trans('Country'), 'country', [
                                'value' => $model->country,
                            ]) }}
                        </div>

                        <div class="col-md-6">
                            {{ Field::text(trans('Username'), 'username', [
                                'value' => $model->username,
                            ]) }}
                        </div>

                        <div class="col-md-6">
                            {{ Field::password(trans('Password'), 'password', [
                                'value' => $model->password,
                            ]) }}
                        </div>
                    </div>
                @endcomponent
            </div>

            <div class="col-md-4">
                @component('core::components.card')
                    {{ Field::select(trans('Status'), 'active', [
                        'options' => [
                            1 => trans('Active'),
                            0 => trans('Inactive'),
                        ],
                        'value' => $model->active ?? 1,
                    ]) }}

                    {{ Field::select(trans('Type'), 'is_free', [
                         'options' => [
                            1 => trans('Free'),
                            0 => trans('Paid'),
                        ],
                        'value' => $model->is_free ?? 1,
                    ]) }}
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection
