@php
    $title = empty($title) ? $field : $title;
    $colspan = empty($colspan) ? 4 : $colspan;
    $favicon = $favicon ?? false;
@endphp
<div class="form-group">
    <label for="image" class="col-sm-2 control-label">
        {!! Form::label($title, trans('admin_labels.' . $title), array('class' => "control-label")) !!}
    </label>
    <div class="col-xs-12 col-sm-7 col-md-10 col-lg-2" id="image-wrap">
        <img
            src="{{isset($model->{$field})
                    ? file_url($model->{$field}, $favicon)
                    : 'https://via.placeholder.com/150'
                 }}"
            id="preview"
            class="img-thumbnail mb-1 lg-1"
            data-default="https://via.placeholder.com/150"
        >

        <p>
            @if($model->{$field} && $model->{$field} !== NULL)
                <button type="button" id="removeImage" class="btn btn-light-warning btn-sm isRemoveImage">{!! __('admin_labels.delete_image') !!}</button>
            @else
                <button type="button" id="removeImage" class="btn btn-light-warning btn-sm isRemoveImage" hidden>{!! __('admin_labels.delete_image') !!}</button>
            @endif
        </p>
        <input class="hide isRemoveImage" type="text" id="isRemoveImage" name="{{$group . '['.$title.'][isRemoveImage]'}}" hidden value="0">

        <input class="input-file" id="input-file" type="file" name="{{$group . '['.$title.'][file]'}}" accept="image/*" style="padding-bottom: 10px">
    </div>
</div>

