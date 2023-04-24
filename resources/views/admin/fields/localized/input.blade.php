@php
    $title = empty($title) ? $field : $title;
    $colspan = empty($colspan) ? 12 : $colspan;
@endphp
<div class="form-row">
    <div class="form-group col-sm-{{$colspan}}">
        {!! Form::label($locale.'['.$field.']', __('admin_labels.' . $title), array('class' => "control-label ml-0")) !!}

        {!! Form::text($locale.'['.$field.']', isset($model->translate($locale)->{$field}) ? $model->translate($locale)->{$field} : '', array('placeholder' => __('admin_labels.' . $title), 'class' => 'form-control input name_'.$locale)) !!}
        {!! $errors->first($locale.'.' . $field, '<span class="error">:message</span>') !!}
    </div>
</div>
