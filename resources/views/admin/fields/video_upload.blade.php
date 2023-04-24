@php
    $title = empty($title) ? $field : $title;
@endphp

<div class="form-group">
    <label for="video" class="col-sm-2 control-label">
        {!! Form::label($field, __('admin_labels.'.$title), array('class' => "control-label")) !!}
    </label>
    <div class="col-xs-12 col-sm-7 col-md-10 resumable-video-uploader"
         data-target="{{ $target }}"
         data-token="{{ $token }}"
         data-type="{{ $type }}"
         data-module="{{$module}}"
         data-ability="{{ isset($model->id) ? 'edit' : 'create' }}"
         data-locale="{{$file_locale ?? null}}"
    >
        @if($model->{$field})<label>{{$video_name}}</label>@endif

        <div class="input-group mb-3">
            <input type="text" name="{{ $field }}" class="form-control video_input" readonly value="{{old($field, $model->{$field}??'')}}">
            <div class="input-group-append">
                <button class="btn btn-primary resumable-browser" type="button" >Brows File</button>
            </div>
        </div>

        <div class="progress mt-3" style="height: 25px; display: none">
            <div class="progress-bar progress-bar-striped progress-bar-animated"
                 role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                 style="width: 75%; height: 100%">75%
            </div>
        </div>

        <div id="upload_div" class="w-100" style="max-width: 500px; display: flex; flex-direction: row-reverse;justify-content: flex-end;">
            @if(isset($model->{$field}))
                <label class="delete_video"
                       style="color: red; display: block; margin-left: 10px; cursor: pointer;"
                       data-id="{!! $model->id !!}"
                       data-token="{!! $token !!}"
                       data-field="{{ $field }}"
                       data-url="{{ $delete_url }}"
                       data-value=''
                       data-message="{{__('admin_labels.video_was_deleted')}}"
                >
                    X
                </label>
            @endif
            <video src="{{$video_url}}" controls style="width: 100%; height: auto"
                   width="470" height="255" poster="@if(!$model->{$field}){{'https://via.placeholder.com/150'}}@endif"
                   class="video_preview img-thumbnail mb-1 lg-1">
            </video>
        </div>
    </div>
</div>
