@extends("admin.layouts.master")
@section('content_header')

    @can($module . '_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-light-success" href="{{ route('admin.' . $module . '.create') }}" style="float: right;">
                    {{__('admin_labels.add')}}
                </a>
            </div>
        </div>
    @endcan

@stop
@section("content")
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="pages-table">
                        {!!
                           TablesBuilder::create(
                               ['id' => "datatable1", 'class' => "table table-hover w-100"]
                           )
                           ->addHead([
                               ['text' => __("admin_labels.id"), 'attr' => ['style' => 'width: 40px']],
                               ['text' => __('admin_labels.name')],
                               ['text' => __('admin_labels.type')],
                               ['text' => __('admin_labels.key')],
                               ['text' => __('admin_labels.attributes.translatable')],
                               ['text' => __('admin_labels.attributes.group')],
                               ['text' => __('admin_labels.attributes.in_group_position')],
                               ['text' => __("buttons.actions"), 'attr' => ['style' => 'width: 95px']]
                           ])
                           ->addFoot([
                               ['attr' => ['colspan' => 7]]
                           ])
                           ->makehtml()
                       !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("js")
    {!! TablesBuilder::create(
      ['id' => "datatable1", 'class' => "table table-hover w-100"],
      [
      'bStateSave' => true,
      'order' => [[ 0, 'desc' ]],
      "columns" => [
          [ "data" => "id" ],
          [ "data" => "name" ],
          [ "data" => "type" ],
          [ "data" => "key" ],
          [ "data" => "translatable" ],
          [ "data" => "group" ],
          [ "data" => "in_group_position" ],
          [ "data" => "actions" ]
          ],
      ])
      ->makejs(); !!}

@endsection


