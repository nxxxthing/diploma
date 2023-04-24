@extends('admin.layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <livewire:roles.roles :model="$model"/>
        </div>
    </div>
@stop
