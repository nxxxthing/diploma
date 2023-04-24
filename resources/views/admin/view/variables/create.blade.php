@extends("admin.layouts.editable")

@section("form-start", Form::open(['route' => "admin.$module.store"]))

@section("form-body")
    @include("admin.view.$module.partials._form")
@endsection
