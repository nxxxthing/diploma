@php
    /**
     * @var string|null $locale
     * @var int $id
     * @var \App\Models\Variable $variable
     */
$locale = $locale ??'';
@endphp

@if ($errors->has($id.'.value'))
    <span class="text-red col-12">
        {{$errors->messages()[$id.'.value'][0]}}
    </span>
@endif

@if ($errors->has($id.'.'.$locale.'.content'))
    <span class="text-red col-12">
        {{$errors->messages()[$id.'.'.$locale.'.content'][0]}}
    </span>
@endif


<div class="col-12">
    @if(\App\Models\Variable::type_TITLE === $variable->type)

        @if(empty($locale))
            <input type="text" class="form-control @if ($errors->has($id.'.value')) is-invalid @endif"
                   name="{{$id}}[value]" placeholder="{{__('admin_labels.attributes.value')}}"
                   value="{{old($id.'.value' , $variable->value ?? '')}}">

        @else
            <input type="text" class="form-control @if ($errors->has($id.'.'.$locale.'.content')) is-invalid @endif"
                   name="{{$id}}[{{$locale}}][content]" placeholder="{{__('admin_labels.attributes.value')}}"
                   value="{{old($id.'.'.$locale.'.content' , $variable->translate($locale)->content ?? '')}}">
        @endif

    @elseif(\App\Models\Variable::type_TEXT === $variable->type)

        @if(empty($locale))
            <textarea name="{{$id}}[value]" placeholder="{{__('admin_labels.attributes.value')}}"
                      class="form-control ckeditor @if ($errors->has($id.'.value')) is-invalid @endif"
            >{{old($id.'.value' , $variable->value ?? '')}}</textarea>
        @else

            <textarea name="{{$id}}[{{$locale}}][content]" placeholder="{{__('admin_labels.attributes.value')}}"
                      class="form-control ckeditor @if ($errors->has($id.'.'.$locale.'.content')) is-invalid @endif"
            >{{old($id.'.'.$locale.'.content'
            , isset($variable->translate($locale)->content)
                 ? $variable->translate($locale)->content
                 : '')}}</textarea>
        @endif

    @elseif(\App\Models\Variable::type_IMAGE === $variable->type)

        @if(empty($locale))
            <div class="">
                <input class="input-file imageInput" type="file" name="{{$id}}[value]" accept="image/*">
                <br>
                @include("admin.partials.image", ['src' => $variable->value ?? '', 'class' => 'img-thumbnail preview mt-3'])
                <p class="pt-3">
                    <button type="button" class="btn btn-warning btn-sm removeImage"
                            @if(empty($variable->value)) hidden @endif>
                        {!! __('buttons.delete') !!}
                    </button>
                    <input class="input-file isRemoveImage" type="hidden" name="{{$id}}[isRemoveImage]" value="0">
                </p>
            </div>
        @else
            <div class="">
                <input class="input-file imageInput" type="file" name="{{$id}}[{{$locale}}][content]" accept="image/*">
                <br>
                @include("admin.partials.image", ['src' => $variable->translate($locale)->content ?? '', 'class' => 'img-thumbnail preview mt-3'])
                <p class="pt-3">
                    <button type="button" class="btn btn-warning btn-sm removeImage"
                            @if(empty($variable->translate($locale)->content)) hidden @endif>
                        {!! __('buttons.delete') !!}
                    </button>
                    <input class="input-file isRemoveImage" type="hidden" name="{{$id}}[{{$locale}}][isRemoveImage]" value="0">
                </p>
            </div>
        @endif


    @elseif(\App\Models\Variable::type_FILE == $variable->type)
        <div class="form-group">

            {!! Form::label($id . '[value]', __('admin_labels.attributes.file'), array('class' => "control-label")) !!}
            @if(empty($locale))
                <div class="col-xs-12 col-sm-7 col-md-10 col-lg-5" id="image-wrap">
                    @if(isset($variable->value) && $variable->value != '')
                        <a
                            href="{{file_url($variable->value)}}"
                            target="_blank"
                            id="preview"
                            class="img-thumbnail file-preview"

                        >
                            {{__('admin_labels.show_file')}}
                        </a>
                        <br><br>
                    @endif
                    <p>
                        @if(isset($variable->value) && $variable->value != '')
                            <button type="button" id="removeImage" class="btn btn-warning btn-sm removeImage">{!! __('admin_labels.delete_file') !!}</button>
                        @else
                            <button type="button" id="removeImage" class="btn btn-warning btn-sm removeImage" hidden>{!! __('admin_labels.delete_file') !!}</button>
                        @endif
                    </p>
                    <input class="hide isRemoveImage" type="text" id="isRemoveImage" name="{{$id}}[isRemoveImage]" hidden value="0">

                    <input class="input-file imageInput" id="input-file" type="file" name="{{$id}}[value]" accept="application/msword,text/plain, application/pdf, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="padding-bottom: 10px">
                </div>
            @else
                <div class="col-xs-12 col-sm-7 col-md-10 col-lg-5" id="image-wrap">
                    @if(isset($variable->translate($locale)->content) && $variable->translate($locale)->content != '')
                        <a
                            href="{{file_url($variable->translate($locale)->content)}}"
                            target="_blank"
                            id="preview"
                            class="img-thumbnail file-preview"

                        >
                            {{__('admin_labels.show_file')}}
                        </a>
                        <br><br>
                    @endif
                    <p>
                        @if(isset($variable->translate($locale)->content) && $variable->translate($locale)->content != '')
                            <button type="button" id="removeImage" class="btn btn-warning btn-sm removeImage">{!! __('admin_labels.delete_file') !!}</button>
                        @else
                            <button type="button" id="removeImage" class="btn btn-warning btn-sm removeImage" hidden>{!! __('admin_labels.delete_file') !!}</button>
                        @endif
                    </p>
                    <input class="hide isRemoveImage" type="text" id="isRemoveImage" name="{{$id}}[{{$locale}}][isRemoveImage]" hidden value="0">

                    <input class="input-file imageInput" id="input-file" type="file" name="{{$id}}[{{$locale}}][content]" accept="application/msword,text/plain, application/pdf, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="padding-bottom: 10px">
                </div>
            @endif
        </div>

    @endif
</div>
