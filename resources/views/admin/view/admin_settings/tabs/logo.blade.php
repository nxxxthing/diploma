<div class="row">
    <div class="col-md-12">
        <div class="card-body">
            <div class="form-group">
                @foreach($settings->where('group', 'logo') as $setting)
                    @if($setting->key == 'logo')
                        <label for="{!! $setting->group.'[' . $setting->key . ']' !!}" class="col-sm-2 control-label">
                            {!! Form::label($setting->group.'[' . $setting->key . ']', __('admin_labels.' . $setting->key), array('class' => "control-label")) !!}
                        </label>

                        <div class="col-xs-6">
                            {!! Form::textarea($setting->group.'[' . $setting->key . ']', $setting->pure_value ?? '', array('placeholder' => __('admin_labels.' . $setting->key), 'class' => 'form-control ckeditor', 'rows' => '5')) !!}
                            {!! $errors->first($setting->group.'[' . $setting->key . ']', '<span class="error">:message</span>') !!}
                        </div>
                    @else
                        @include(
                                    'admin.fields.image',
                                    [
                                        'title' => $setting->key,
                                        'group' => $setting->group,
                                        'model' => $setting,
                                        'name'  => $setting->group . '[' . $setting->key . ']',
                                        'field' => 'pure_value',
                                        'colspan' => 10,
                                        'favicon' => $setting->value == $setting->default
                                    ]
                        )
                    @endif

                @endforeach
            </div>
        </div>
    </div>
</div>
