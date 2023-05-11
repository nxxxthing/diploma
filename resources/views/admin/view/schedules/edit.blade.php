@extends('admin.layouts.editable')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <livewire:schedules.schedules :model="$model"/>
        </div>
    </div>
    <a class="btn btn-light-secondary" href="{{route('admin.groups.edit', ['group' => $group])}}">
        {{__('admin_labels.buttons.back')}}
    </a>
@endsection
