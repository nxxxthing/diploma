@php
    $title = empty($title) ? $field : $title;
    $colspan = empty($colspan) ? 12 : $colspan;
@endphp
<div class="form-row">
    <div class="form-group col-sm-{{ $colspan }}">
        {!! Form::label($field, __('admin_labels.'.$title), array('class' => "control-label")) !!}

        {!! Form::select($field, $values, $model->{$field}, array('class' => 'form-control')) !!}
        {!! $errors->first($field, '<span class="error">:message</span>') !!}
    </div>
</div>
