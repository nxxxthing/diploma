@extends('admin.layouts.listable')
@section('content')
    @if(isset($test_notifications) && $test_notifications)
        @if(isset($message))
            {{$message}}
        @else
            @if($was_created)
                <h3>Successfully created {{request()->get('count')}} notification for user : {{request()->get('email')}}</h3>
            @endif
            <form class="period-filter" action="{{route('admin.home')}}" method="GET">
                <input type="hidden" name="test_notifications" value="1">
                <input type="hidden" name="email" value="{{request()->get('email')}}">
                <br/>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="category-slug">{{__('admin_labels.unit')}}. <br>
                            If empty - <a href="{{route('admin.units.create')}}">create</a> unit where Admin unit (owner) will be {{request()->get('email')}}
                        </label>
                        <select class="form-control input-sm select2" name="unit_id">
                            @foreach($user->units->pluck('title', 'id') as $key => $unit)
                                <option
                                    value="{{$key}}"
                                @if($key == request()->get('unit_id'))
                                    {{'selected'}}
                                    @endif
                                >
                                    {{$unit}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="category-slug">{{__('admin_labels.notification_type')}}</label>
                        <select class="form-control input-sm select2" name="notification_type">
                            @foreach($notification_types as $key => $type)
                                <option
                                    value="{{$key}}"
                                @if($key == request()->get('notification_type'))
                                    {{'selected'}}
                                    @endif
                                >
                                    {{$type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="category-slug">
                           Count
                        </label>
                        {!! Form::number('count', request()->get('count'), array('class' => 'form-control col-xs-1', 'required' => 'required')) !!}
                    </div>
                </div>
                <br/>
                <div class="row mt-2">
                    <div class="col-sm-4" style="display: flex; align-items: flex-end">
                        <button type="submit" style="line-height: 1.3" class="btn btn-md btn-success"> Generate </button>
                    </div>
                </div>


            </form>
        @endif
    @else
        <h1>{{__('admin_labels.hello')}}</h1>
    @endif
@endsection
