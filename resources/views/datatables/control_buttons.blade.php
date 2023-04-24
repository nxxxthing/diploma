@can($type.'_show')
<a class="btn btn-xs btn-light-secondary" href="{{$type .'/'. $model->id}}">
    {{__('datatables.show')}}
</a>
@endcan
@can($type.'_edit')
<a class="btn btn-xs btn-light-info" href="{{$type .'/'. $model->id . '/edit'}}">
    {{__('datatables.edit')}}
</a>
@endcan
@can($type.'_delete')
<form action="{{ route('admin.'.$type.'.destroy', $model->id) }}" method="POST" onsubmit="return confirm('{{__('admin_labels.confirm_delete')}}');" style="display: inline-block;">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="btn btn-xs btn-light-danger" value="{{__('datatables.delete')}}">
</form>
@endcan

