@extends('admin.layouts.listable')

@section('content_header')
    @can('progress_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.progress.create") }}">
                    {{__('admin_labels.add')}}
                </a>
            </div>
        </div>
    @endcan
@stop

@section('content')
    <livewire:progress.student.progress-list/>
@endsection
