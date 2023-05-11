@extends('admin.layouts.editable')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <livewire:progress.student.progress-form :model="$model"/>
        </div>
    </div>
@endsection
