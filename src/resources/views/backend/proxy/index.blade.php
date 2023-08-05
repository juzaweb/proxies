@extends('cms::layouts.backend')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group float-right">
                <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#import-modal">
                    <i class="fa fa-plus-circle"></i> Import
                </a>
                <a href="{{ $linkCreate }}" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> {{ trans('cms::app.add_new') }}
                </a>
            </div>
        </div>
    </div>

    {{ $dataTable->render() }}

    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ admin_url('ajax/proxies/import') }}" class="form-ajax" data-notify="true">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Import proxies') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        {{ Field::textarea(
                            __('Proxies'),
                            'proxies',
                              [
                                  'cols' => 30,
                                  'rows' => 10,
                                  'placeholder' => __('One proxy per line. Import format example: 127.0.0.1:8080'),
                              ]
                        ) }}
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
