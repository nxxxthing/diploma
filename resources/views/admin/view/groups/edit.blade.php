@extends('admin.layouts.editable')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <livewire:groups.groups :model="$model"/>
        </div>
    </div>
    @can('schedules_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.schedules.create", ['group' => $model]) }}">
                    {{__('admin_labels.add')}}
                </a>
            </div>
        </div>
    @endcan
    <livewire:schedules.schedule-list :group="$model"/>
@endsection
