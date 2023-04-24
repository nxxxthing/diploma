@php
    $title = empty($title) ? $field : $title;
    $withRemove = $withRemove ?? true;
    $accept = $accept ?? 'application/msword,text/plain, application/pdf, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel';
@endphp
<div class="form-group">

    {!! Form::label($field, __('admin_labels.'.$title), array('class' => "control-label")) !!}

    <div class="col-xs-12 col-sm-7 col-md-10 col-lg-5" id="image-wrap">
        @if(isset($model) && $model->{$field})
            <a
                href="{{file_url($model->{$field})}}"
                target="_blank"
                id="preview"
                class="img-thumbnail file-preview"

            >
                {{__('admin_labels.show_file')}}
            </a>
            <br><br>
        @endif
        <p>
            @if ($withRemove)
                @if(isset($model->{$field}) && $model->{$field} && $model->{$field} !== NULL)
                    <button type="button" id="removeImage" class="btn btn-warning btn-sm removeImage">{!! __('admin_labels.delete_file') !!}</button>
                @else
                    <button type="button" id="removeImage" class="btn btn-warning btn-sm removeImage" hidden>{!! __('admin_labels.delete_file') !!}</button>
                @endif
            @endif
        </p>
        <input class="hide isRemoveImage" type="text" id="isRemoveImage" name="{{$remove_field ?? 'isRemoveImage'}}" hidden value="0">

        <input class="input-file imageInput" id="input-file" type="file" name="{{$field_name ?? $field}}" accept="{{$accept}}" style="padding-bottom: 10px">
    </div>
</div>
