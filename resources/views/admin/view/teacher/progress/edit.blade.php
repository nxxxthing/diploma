@extends('admin.layouts.editable')

@section('content')
    <table class="table table-bordered">
        <tr>
            <th style="width: 15%">
                {{__('admin_labels.title')}}
            </th>
            <td>
                {{$model->title}}
            </td>
        </tr>
        <tr>
            <th style="width: 15%">
                {{__('admin_labels.description')}}
            </th>
            <td>
                {!! $model->description !!}
            </td>
        </tr>
        @if($model->type == \App\Enums\ProgressTypes::TEXT->value)
            <tr>
                <th style="width: 15%">
                    {{__('admin_labels.text')}}
                </th>
                <td>
                    {!! $model->text !!}
                </td>
            </tr>
        @elseif($model->type == \App\Enums\ProgressTypes::IMAGE->value)
            <tr>
                <th style="width: 15%">
                    {{__('admin_labels.image')}}
                </th>
                <td>
                    <a href="{{file_url($model->image)}}" target="_blank">
                    <img style="max-width: 500px; max-height: 500px; min-width: 100px; min-height: 100px" src="{{file_url($model->image)}}" alt="{{$model->title}}"/>
                    </a>
                </td>
            </tr>
        @elseif($model->type == \App\Enums\ProgressTypes::FILE->value)
            <tr>
                <th style="width: 15%">
                    {{__('admin_labels.file')}}
                </th>
                <td>
                    <a class="btn btn-primary" href="{{route('admin.progress.download', $model)}}" target="_blank">{{__('admin_labels.download')}}</a>
                </td>
            </tr>
        @endif
    </table>
    <div class="row">
        <div class="col-lg-12">
            <livewire:progress.teacher.progress-form :model="$model"/>
        </div>
    </div>
@endsection
