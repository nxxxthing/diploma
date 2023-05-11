@extends('admin.layouts.editable')

@section('content')
    @if(isset($model->result))
        <table class="table table-bordered">
            <tr>
                <th style="width: 15%">
                    {{__('admin_labels.result')}}
                </th>
                <td>
                    {{$model->result}}
                </td>
            </tr>
            @if ($model->comment)
                <tr>
                    <th style="width: 15%">
                        {{__('admin_labels.comment')}}
                    </th>
                    <td>
                        {!! $model->comment !!}
                    </td>
                </tr>
            @endif
        </table>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <livewire:progress.student.progress-form :model="$model"/>
        </div>
    </div>
@endsection
