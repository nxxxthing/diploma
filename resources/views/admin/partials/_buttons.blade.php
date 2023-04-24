@php
    $sticky = $sticky ?? false;
@endphp

<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif @if ($sticky) bottom-line-sticky @endif">
    <div class="col-md-6">
        @if($module != 'translation')
        <a href="{!! empty($back_url) ? route('admin.' . $module . '.index') : $back_url !!}" class="btn btn-dark">{{__('admin_labels.buttons.back')}}</a>
        @endif
    </div>

    <div class="col-md-6 justify-content-end text-right">
        <input class="btn btn-success btn-flat button" type="submit" value="{{__('admin_labels.buttons.save')}}" style="border-radius: 0.25rem">
    </div>
</div>
