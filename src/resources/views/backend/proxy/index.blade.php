@extends('core::layouts.admin')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="btn-group float-right">
                <a href="{{ route('proxies.create') }}" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> {{ trans('core::app.add_new') }}
                </a>
            </div>
        </div>
    </div>

    {!! $dataTable->table() !!}

@endsection

@section('scripts')
    {!! $dataTable->scripts() !!}
@endsection
