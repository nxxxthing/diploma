@php
    $title = empty($title) ? $field : $title;
    $without_remove = $without_remove ?? false;
@endphp
<div class="form-group">

    {!! Form::label($field, __('admin_labels.'.$title), array('class' => "control-label")) !!}

    <div class="col-xs-12 col-sm-7 col-md-10 col-lg-5 p-0" id="image-wrap">
        <img
            src="{{isset($model->{$field}) && $model->{$field} != ''
                    ? file_url($model->{$field})
                    : 'https://via.placeholder.com/150'
                 }}"
            id="preview"
            class="img-thumbnail mb-1 lg-1"
            data-default="https://via.placeholder.com/150"
        >

        <p>
            @if(!$without_remove)
                @if(isset($model->{$field}) && $model->{$field})
                    <button type="button" id="removeImage"
                            class="btn btn-warning btn-sm removeImage">{!! __('admin_labels.delete_image') !!}</button>
                @else
                    <button type="button" id="removeImage" class="btn btn-warning btn-sm removeImage"
                            hidden>{!! __('admin_labels.delete_image') !!}</button>
                @endif
            @endif
        </p>
        @if(!$without_remove)
            <input class="hide isRemoveImage" type="text" id="isRemoveImage" name="{{$remove_field ?? 'isRemoveImage'}}"
                   hidden value="0">
        @endif
        @if($with_update ?? true)
            <input class="input-file imageInput" id="input-file" type="file"
                   name="{{$field_name ?? $field}}" accept="image/*" style="padding-bottom: 10px">
        @endif
    </div>
</div>
