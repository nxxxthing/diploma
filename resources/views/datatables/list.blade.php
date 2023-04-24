<ul>
    @if($model->address_id)
    @switch($delivery_type)
        @case(1)
    <li>{{$address->city}}</li>
    <li>{{$address->department}}</li>
        <li>{{$phone}}</li>
        @break
        @case(2)
        <li>{{$address->city}}</li>
        <li>{{$address->index}}</li>
        <li>{{$phone}}</li>
        @break
        @case(3)
        @if(isset($address->country))<li>{{$address->country}}</li>@endif
        @if(isset($address->city))<li>{{$address->city}}</li>@endif
        @if(isset($address->street))<li>{{$address->street}}</li>@endif
        @if(isset($address->house))<li>{{$address->house}}</li>@endif
        @if(isset($address->flat))<li>{{$address->flat}}</li>@endif
        <li>{{$phone}}</li>
        @break
            @case(4)
            {{__('admin_labels.pickup')}}
            @break
        @endswitch
    @else
        @switch($delivery_type)
            @case(3)
        @if($address['country'])<li>{{$address['country']}}</li>@endif
        @if($address['city'])<li>{{$address['city']}}</li>@endif
        @if($address['street'])<li>{{$address['street']}}</li>@endif
        @if($address['house'])<li>{{$address['house']}}</li>@endif
        @if($address['flat'])<li>{{$address['flat']}}</li>@endif
            <li>{{$phone}}</li>
        @break
            @case(4)
            {{__('admin_labels.pickup')}}
            @break
        @endswitch
    @endif
</ul>
