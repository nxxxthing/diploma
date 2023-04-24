@extends('admin.layouts.listable')

@php
    $message = __('admin_labels.are_you_sure_default_settings')
@endphp

@section('content_header')
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-4">
                {{__('admin_labels.admin_settings')}}
            </div>
            <div class="col-4">
                <form
                    action="{{ route('admin.' . $module . '.set_default') }}"
                    method="POST"
                    onsubmit="return confirm('{{$message}}')"
                    style="display: inline-block;"
                >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-light-secondary" value="{{__('admin_labels.set_default_settings')}}">
                </form>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form data-toggle="validator" action="{{ route("admin." . $module . ".update") }}" method="POST" enctype="multipart/form-data" role = "form" style="padding: 0 15px">
                @csrf
                @method('PUT')
                @include('admin.view.' . $module . '.partials._form')
            </form>
        </div>
    </div>
@endsection
