@extends("admin.layouts.editable")

@php
    /**
     * @var \App\Models\Variable $model
     * @var string $module
     */
@endphp

@section("form-start", Form::model($model, ['route' => ["admin.$module.update", $model->id], 'method' => 'put']))

@section("form-body")
    @include("admin.view.$module.partials._form")
@endsection
