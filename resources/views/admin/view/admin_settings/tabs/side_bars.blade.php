@php
    $sidebars = $settings->filter(function ($item) {
        return str_contains($item->group, 'side_bars');
    });
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card-body">
             @foreach ($sidebars as $key => $setting)
                 @if(in_array($setting->group, ['side_bars_left_side_navbar', 'side_bars_top_sidebar', 'side_bars_left_side_navbar_text_hover']))
                    <label for="{!! $setting->key.'[' . $setting->group . ']'!!}" class="col-sm-2 control-label">
                        {!! Form::label($setting->key.'[' . $setting->group . ']', __('admin_labels.' . $setting->group), array('class' => "control-label")) !!}
                    </label>

                    <div class="col-md-5 color-pick">
                        <select name="{{$setting->key.'[' . $setting->group . ']'}}" style="width: 100%" id="theme{{$key}}" class="theme">
                            @foreach(\App\Models\AdminSetting::$colors as $color_key => $value)
                                <option value="{{$value}}" @if($setting->pure_value == $color_key) selected @endif>
                                    {{".  "}}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first($setting->key.'[' . $setting->group . ']', '<span class="error">:message</span>') !!}
                    </div>
                @else
{{--                     @dd($setting->pure_value)--}}
                    <div class="form-group">
                        <label for="{!! $setting->key.'[' . $setting->group . ']' !!}" class="col-sm-2 control-label">
                            {!! Form::label($setting->key.'[' . $setting->group . ']', __('admin_labels.' . $setting->group), array('class' => "control-label")) !!}
                        </label>
                        <div class="col-md-5">
                            <div class="col-xs-2">
                                {!! Form::select($setting->key.'[' . $setting->group . ']', ['light' => __('admin_labels.dark'), 'dark' =>  __('admin_labels.light')], $setting->pure_value, array('class' => 'form-control col-xs-1')) !!}
                            </div>
                            {!! $errors->first($setting->key.'[' . $setting->group . ']', '<span class="error">:message</span>') !!}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
