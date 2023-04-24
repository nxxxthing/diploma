@php
     $title = empty($title) ? $field : $title;
     $colspan = empty($colspan) ? 4 : $colspan;
@endphp
<div class="form-row">
    <div class="form-group col-sm-{{ $colspan }}">
        {!! Form::label($field, __('admin_labels.'.$title), array('class' => "control-label")) !!}

        {!! Form::text($field, isset($model->{$field}) ? $model->{$field} : '', array('placeholder' => __('admin_labels.' . $title), 'class' => 'form-control form-control-sm input-sm name_'.$locale)) !!}
        {!! $errors->first($field, '<span class="error">:message</span>') !!}
    </div>
</div>

