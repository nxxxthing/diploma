@php
    /**
     * @var \App\Models\Variable $model
     */
@endphp

<div class="form-group required row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('type', __('admin_labels.attributes.type')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::select('type', $types, null, ['class'=>"form-control" ]) !!}
        @if ($errors->has('type'))
            <span style="color:red">{{$errors->messages()['type'][0]}}</span>
        @endif
    </div>
</div>

<div class="form-group required row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('key', __('admin_labels.attributes.key')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::text('key', null, ['placeholder' => __('admin_labels.attributes.key'),
            'class' => 'form-control'.($errors->has('key')?' is-invalid':'')]) !!}
        @isset($model)
            <span class="text-danger">{{__('admin_labels.attributes.be_careful_when_changing_this_setting')}}</span>
        @endif
        @if ($errors->has('key'))
            <span style="color:red">{{$errors->messages()['key'][0]}}</span>
        @endif
    </div>
</div>

<div class="form-group required row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('name', __('admin_labels.attributes.name')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::text('name', null, ['placeholder' => __('admin_labels.attributes.name'),
            'class' => 'form-control'.($errors->has('name')?' is-invalid':'')]) !!}

        @if ($errors->has('name'))
            <span style="color:red">{{$errors->messages()['name'][0]}}</span>
        @endif
    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('description', __('admin_labels.attributes.description')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::text('description', null, ['placeholder' => __('admin_labels.attributes.description'),
            'class' => 'form-control'.($errors->has('description')?' is-invalid':'')]) !!}

        @if ($errors->has('description'))
            <span style="color:red">{{$errors->messages()['description'][0]}}</span>
        @endif
    </div>
</div>

<div class="form-group required row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('translatable', __('admin_labels.attributes.translatable')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::select('translatable', ['0' => __('admin_labels.no'), '1' => __('admin_labels.yes')], null, ['class'=>"form-control" ]) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('status', __('admin_labels.attributes.status')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::select('status', ['1' => __('admin_labels.status_on'), '0' => __('admin_labels.status_off'),], null, ['class'=>"form-control" ]) !!}
    </div>
</div>

<hr>

<div class="form-group row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('group', __('admin_labels.attributes.group')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::text('group', null,['placeholder' => __('admin_labels.attributes.group'),
            'class' => 'form-control'.($errors->has('group')?' is-invalid':''),
            'list'=>'group_datalist']) !!}
        {!! Form::datalist('group_datalist', $groups ?? []) !!}

        @if ($errors->has('group'))
            <span style="color:red">{{$errors->messages()['group'][0]}}</span>
        @endif
    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-md-right">
        {!! Form::label('in_group_position', __('admin_labels.attributes.in_group_position')) !!}
    </div>
    <div class="col-md-10">
        {!! Form::number('in_group_position', $model->in_group_position??1,
['placeholder' => __('admin_labels.attributes.in_group_position'),
'class' => 'form-control'.($errors->has('in_group_position')?' is-invalid':''), 'min' => 1]) !!}

        @if ($errors->has('in_group_position'))
            <span style="color:red">{{$errors->messages()['in_group_position'][0]}}</span>
        @endif
    </div>
</div>


