@php
    $width = $width ?? '100px';
    $height = $height ?? '100px';
@endphp

@if(isset($src))
<img src="{{asset($src)}}" alt="" style="width: {{$width}}; height: {{$height}}">

@endif
