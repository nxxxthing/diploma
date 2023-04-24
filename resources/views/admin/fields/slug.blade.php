@php
    $field = $field ?? 'slug';
    $title = $title ?? __('admin_labels.slug');
@endphp

<div class="form-group">
    <label for="slug" class="col-sm-2 control-label">
        {!! Form::label($field, $title, array('class' => "control-label")) !!}
    </label>

    <div class="col-sm-10">
        <div class="col-xs-6">
            {!! Form::text($field, $model->{$field}, array('placeholder' => $title, 'class' => 'form-control input-sm')) !!}
        </div>
        {!! $errors->first($field, '<span class="error">:message</span>') !!}
        @if($with_generate ?? true)
            <br>
            <p>
                <button type="button"
                        class="btn btn-light-success btn-sm slug-generate">{!! __('admin_labels.buttons.generate') !!}</button>
            </p>
        @endif
    </div>
</div>
