@extends('admin.layouts.editable')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <livewire:users.users-self :model="$model"/>
        </div>
    </div>
@endsection
