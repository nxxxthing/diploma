@php
    $title = empty($title) ? $field : $title;
    $class = $class ?? 'ckeditor';
@endphp
<div class="form-row">
    <div class="form-group col-xs-6">
        {!! Form::label($field_name, __('admin_labels.' . $title), array('class' => "control-label")) !!}

        {!! Form::textarea($field_name, isset($model?->translate($locale)->{$field}) ? $model->translate($locale)->{$field} : '', array('placeholder' => __('admin_labels.' . $title), 'class' => "form-control $class", 'rows' => '5')) !!}
        {!! $errors->first($field_name, '<span class="error">:message</span>') !!}
    </div>
</div>
