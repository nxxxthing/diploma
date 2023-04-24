@extends('admin.layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <livewire:module.module :model="$model"/>
        </div>

        <div class="col-lg-12">
            <livewire:module.module-content :module="$model"/>
        </div>
    </div>
@stop
