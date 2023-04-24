<div class="row">
    <div class="col-md-12">
        <div class="card-body">
            <div class="form-group">
                @foreach($settings->where('group', 'admin_title') as $setting)
                    @if($setting->key == 'favicon')
                        @include(
                                    'admin.fields.image',
                                    [
                                        'title' => $setting->key,
                                        'group' => $setting->group,
                                        'model' => $setting,
                                        'name'  => $setting->group . '[' . $setting->key . ']',
                                        'field' => 'pure_value',
                                        'colspan' => 10,
                                        'favicon' => true
                                    ]
                        )
                    @else
                        <label for="position" class="col-sm-2 control-label">
                            {!! Form::label($setting->key, __('admin_labels.' . $setting->key),array('class' => "control-label")) !!}
                        </label>
                        <div class="col-sm-10">
                            <div class="col-xs-1">
                                {!! Form::text(
                                            $setting->group.'[' . $setting->key . ']',
                                            $setting->pure_value ?? '',
                                            array('placeholder' => __('admin_labels.' .  $setting->key),
                                            'class' => 'form-control input-sm'))
                                !!}
                            </div>
                            {!! $errors->first($setting->key, '<span class="error">:message</span>') !!}
                        </div>
                    @endif
                @endforeach
            </div>

        </div>
    </div>
</div>
