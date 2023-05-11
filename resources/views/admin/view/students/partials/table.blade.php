<table class="table table-bordered table-striped">
    <thead>
        <tr>
{{--            <th> {{__('admin_labels.day')}} </th>--}}
            <th style="width: 15%"> {{__('admin_labels.time')}} </th>
            <th> {{__('admin_labels.lesson')}} </th>
            <th> {{__('admin_labels.teacher')}} </th>
        </tr>
    </thead>
    <tbody>
    @php
        $previousDay = '';
    @endphp
        @foreach($tableData as $data)
            @if ($previousDay != $data->day)
                <tr>
                    <td colspan="3" style="text-align: center; background: #77b3f6">{{__("admin_labels.days.{$data->day}")}}</td>
                </tr>
                @php
                    $previousDay = $data->day
                @endphp
            @endif
            <tr>
{{--                <td> {{__("admin_labels.days.{$data->day}")}} </td>--}}
                <td> {{$data->time}} </td>
                <td> {{$data->lesson?->title}} </td>
                <td> {{$data->teacher?->full_name}} </td>
            </tr>
        @endforeach
    </tbody>
</table>
