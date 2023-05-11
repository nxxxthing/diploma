@extends('admin.layouts.master')

@php
    $layout_type = isset($model) ? 'edit' : 'add';
@endphp

@section("content")


    <div class="card mb-3">
        <div class="card-body">

            @yield('form-body')

        </div>
    </div>

@stop
