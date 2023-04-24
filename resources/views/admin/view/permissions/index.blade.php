@extends('admin.layouts.listable')

@section('content_header')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.' . $module . '.create') }}">
                {{__('admin_labels.add')}}
            </a>
        </div>
    </div>

@stop

@section('content')
    <livewire:permissions.permission-list/>
@endsection
