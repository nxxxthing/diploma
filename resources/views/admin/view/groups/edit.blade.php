@extends('admin.layouts.editable')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <livewire:groups.groups :model="$model"/>
        </div>
    </div>
@endsection
