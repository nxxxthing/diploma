@php
     $title = empty($title) ? $field : $title;
     $colspan = empty($colspan) ? 12 : $colspan;
     $min = $min ?? 0;
     $max = $max ?? 10000;
     $step = $step ?? 1
@endphp
<div class="form-row">
    <div class="form-group col-sm-{{ $colspan }}">
        {!! Form::label($field_name, __('admin_labels.'.$title), array('class' => "control-label")) !!}

        {!! Form::number(
                        $field_name,
                        $model->{$field} ?? 0,
                        array(
                            'placeholder' => __('admin_labels.' . $title),
                            'class' => 'form-control input',
                            'min'  => $min,
                            'max'  => $max,
                            'step' => $step
                            )
                        )
        !!}
        {!! $errors->first($field_name, '<span class="error">:message</span>') !!}
    </div>
</div>

