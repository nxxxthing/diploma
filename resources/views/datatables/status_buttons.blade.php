
<a class="btn btn-xs btn-info">
    {{ __('admin_labels.statuses.'.$type)}}
</a>

{{--
@can($type.'_edit')
<a class="btn btn-xs btn-info" href="{{$type .'/'. $model->id . '/edit'/*.($type == 'product')?'?type='.$template:''*/}}">
   Редактировать
</a>
@endcan
@can($type.'_delete')
<form action="{{ route('admin.'.$type.'.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Вы уверены что хотите выдалить?');" style="display: inline-block;">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="btn btn-xs btn-danger" value="Удалить">
</form>
@endcan
--}}
