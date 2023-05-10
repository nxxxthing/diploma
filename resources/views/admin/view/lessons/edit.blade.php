@extends('admin.layouts.editable')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <livewire:lessons.lessons :model="$model"/>
        </div>
    </div>
@endsection
