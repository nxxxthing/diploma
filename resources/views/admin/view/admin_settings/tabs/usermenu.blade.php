<div class="row">
    <div class="col-md-12">
        <div class="card-body">
            @foreach($settings->where('group', 'usermenu') as $key => $setting)
                @if(in_array($setting->key, ['usermenu_enabled', 'usermenu_header']))
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="{{$setting->group.'[' . $setting->key . ']'}}"
                               value="0">
                        <input type="checkbox" class="custom-control-input" @if($setting->pure_value) checked
                               @endif id="{{$key}}" name="{{$setting->group.'[' . $setting->key . ']'}}"
                               value="{{boolval($setting->pure_value)}}">

                        <label class="custom-control-label"
                               for="{{$key}}">{{__('admin_labels.' . $setting->key)}}</label>
                    </div>
                    <br>
                @else
                        <label for="{!! $setting->group.'[' . $setting->key . ']'!!}" class="col-sm-2 control-label">
                            {!! Form::label($setting->group.'[' . $setting->key . ']', __('admin_labels.' . $setting->key), array('class' => "control-label")) !!}
                        </label>

                        <div class="col-md-5 color-pick">
                            <select name="{{$setting->group.'[' . $setting->key . ']'}}" style="width: 100%" id="theme" class="theme">
                                @foreach(\App\Models\AdminSetting::$colors as $color_key => $value)
                                    <option value="{{$value}}" @if($setting->pure_value == $color_key) selected @endif>
                                        {{".  "}}
                                    </option>
                                @endforeach
                            </select>
                            {!! $errors->first($setting->group.'[' . $setting->key . ']', '<span class="error">:message</span>') !!}
                        </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
