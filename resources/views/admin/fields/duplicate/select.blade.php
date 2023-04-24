@php
    $title = empty($title) ? $field : $title;
    $colspan = empty($colspan) ? 12 : $colspan;
@endphp
<div class="form-row">
    <div class="form-group col-sm-{{ $colspan }}">
        {!! Form::label($field_name, __('admin_labels.'.$title), array('class' => "control-label")) !!}

        {!! Form::select($field_name, $values, $model?->{$field}, array('class' => 'form-control')) !!}
        {!! $errors->first($field_name, '<span class="error">:message</span>') !!}
    </div>
</div>
