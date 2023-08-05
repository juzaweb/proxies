@extends('cms::layouts.backend')

@section('content')
    @component('cms::components.form_resource', [
        'model' => $model
    ])

        <div class="row">
            <div class="col-md-8">

                {{ Field::text($model, 'ip', ['label' => trans('jwpr::content.ip')]) }}

                {{ Field::text($model, 'port', ['label' => trans('jwpr::content.port')]) }}

                {{ Field::text($model, 'protocol', ['label' => trans('jwpr::content.protocol')]) }}

                {{ Field::text($model, 'country', ['label' => trans('jwpr::content.country')]) }}

            </div>

            <div class="col-md-4">
                {{ Field::checkbox($model, 'active', ['label' => trans('jwpr::content.active'), 'checked' => $model->active]) }}
            </div>

        </div>

    @endcomponent
@endsection
