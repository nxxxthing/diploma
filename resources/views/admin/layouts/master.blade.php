@extends('adminlte::page')
@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ mix('css/admin/tailwind.css') }}"/>
    <link rel="stylesheet" href="{{ mix('css/admin/app.min.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css" integrity="sha512-8vq2g5nHE062j3xor4XxPeZiPjmRDh6wlufQlfC6pdQ/9urJkU07NM0tEREeymP++NczacJ/Q59ul+/K2eYvcg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>[x-cloak] { display: none !important; }</style>
    @toastr_css
    @livewireStyles
@endpush
@push('js')
    <script src="{{ mix('js/admin/app.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    @toastr_js
    @toastr_render
    @livewireScripts
    <script src="{{ mix('js/admin/alpine.js') }}"></script>
@endpush

@push('body')
    <div class="__outer_loader">
        <div class="loader"></div>
    </div>
@endpush
