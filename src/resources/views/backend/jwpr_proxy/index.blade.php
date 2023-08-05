@extends('cms::layouts.backend')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group float-right">
                <a href="javascript:void(0)" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> Import
                </a>
                <a href="{{ $linkCreate }}" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> {{ trans('cms::app.add_new') }}
                </a>
            </div>
        </div>
    </div>

    {{ $dataTable->render() }}

@endsection
