@extends('cms::layouts.backend')

@section('content')
    @component('cms::components.form_resource', [
        'model' => $model
    ])

        <div class="row">
            <div class="col-md-12">

                {{ Field::text($model, 'ip') }}

                {{ Field::text($model, 'port') }}

                {{ Field::text($model, 'protocol') }}

                {{ Field::text($model, 'country') }}

            </div>
        </div>

    @endcomponent
@endsection
