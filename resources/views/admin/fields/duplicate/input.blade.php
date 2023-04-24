@php
     $title = empty($title) ? $field : $title;
     $colspan = empty($colspan) ? 12 : $colspan;
@endphp
<div class="form-row">
    <div class="form-group col-sm-{{ $colspan }}">
        {!! Form::label($field_name, __('admin_labels.'.$title), array('class' => "control-label")) !!}

        {!! Form::text($field_name, isset($model->{$field}) ? $model->{$field} : '', array('placeholder' => __('admin_labels.' . $title), 'class' => 'form-control name_'.$locale)) !!}
        {!! $errors->first($field_name, '<span class="error">:message</span>') !!}
    </div>
</div>

