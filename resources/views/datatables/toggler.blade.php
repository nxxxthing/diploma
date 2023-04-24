<input id="ajax_ckeckbox" type="checkbox" class="toogle ajax_ckeckbox"
       data-id="{!! $model->id !!}"
       data-token="{!! csrf_token() !!}"
       data-field="{!! $field !!}"
       data-url="{!! route('admin.' . $type . '.ajax_field', ['id' => $model->id]) !!}"
       data-value="{!! $model->{$field} == true ? false : true !!}"
    {!! ($model->{$field} ? 'checked="checked"' : '') !!} />
