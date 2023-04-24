@extends('admin.layouts.editable')

@section('assets.top')
    @parent
    <script src="{!! asset('assets/components/sysTranslit/js/jquery.synctranslit.min.js') !!}"></script>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-12">
                <livewire:module.module :model="$model"/>
            </div>
        </div>
    </div>
@stop
