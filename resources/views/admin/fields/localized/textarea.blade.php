@php
    $title = empty($title) ? $field : $title;
@endphp
<div class="form-row">
    <div class="form-group col-xs-6">
        {!! Form::label($locale.'['.$field.']', __('admin_labels.' . $title), array('class' => "control-label")) !!}

        {!! Form::textarea( $locale.'['.$field.']', isset($model->translate($locale)->{$field}) ? $model->translate($locale)->{$field} : '', array('placeholder' => __('admin_labels.' . $title), 'class' => 'form-control ckeditor', 'rows' => '5')) !!}
        {!! $errors->first($locale.'.' . $field, '<span class="error">:message</span>') !!}
    </div>
</div>
