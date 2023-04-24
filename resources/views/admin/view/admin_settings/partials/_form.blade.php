@include('admin.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">
        <div class="card-body">
            <ul class="nav nav-tabs " id="custom-content-below-tab" role="tablist">
                @foreach($groups as $key => $group)
                    <li class="nav-item">
                        <a class="nav-link @if($key == 0) active @endif" data-toggle="pill" href="#tab_{{$group}}"
                           role="tab" aria-controls="tab_{{$group}}" aria-selected="{{$key == 0}}">
                            {{__('admin_labels.tab_' . $group)}}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content pt-4 pb-4 pl-2 pr-2">
                @foreach($groups as $key => $group)
                    <div class="tab-pane @if($key == 0) active show @endif" id="tab_{{$group}}">
                        @include('admin.view.' . $module . '.tabs.' . $group)
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

@include('admin.partials._buttons')
