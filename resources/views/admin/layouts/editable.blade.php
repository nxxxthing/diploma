@extends('admin.layouts.master')

@php
    $layout_type = isset($model) ? 'edit' : 'add';
@endphp

@section("content")

    @yield('form-start', $model->id
        ? Form::model($model, ['route' => ["admin.$module.update", $model->id], 'method' => 'put', 'files' => View::getSection('with_files', false)])
        : Form::open(['route' => "admin.$module.store", 'files' => View::getSection('with_files', false)])
    )

    <div class="row mb-3">
        @include('admin.partials._buttons', ['class' => 'buttons-top'])
    </div>

    <div class="card mb-3">
        <div class="card-body">

            @yield('form-body')

        </div>
    </div>

    <div class="row mb-3">
        @include('admin.partials._buttons')
    </div>

    @yield('form-close', Form::close())

@stop
