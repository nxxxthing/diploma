@extends("admin.layouts.master")

@php
    /**
     * @var \Illuminate\Database\Eloquent\Collection $variable_groups
     * @var \App\Models\Variable $variable
     */
@endphp

@section("content")

    <div class="col-12 row mt-3">

        <div class="col-md-3 col-lg-2 mb-3">
            <ul class="nav row-md flex-md-column" role="tablist" aria-orientation="vertical">
                @foreach($variable_groups ?? [] as $name => $variables)
                    @php $name = $name ?? "no_name"; @endphp
                    <li class="nav-item row-lg">
                        <a class="col-lg-12 btn btn-md btn-flat btn-outline-info text-lg-right {{$loop->first?'active':''}}"
                           aria-selected="{{$loop->first?'true':'false'}}" data-toggle="tab" role="tab"
                           href="#tab_{{$name}}"
                           aria-controls="tab_{{$name}}">
                            @php
                                $tab = __("admin_labels.variable_tab.$name");
                                if($tab == "admin_labels.variable_tab.$name") $tab = $name;
                            @endphp
                            {{$tab}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col">
            <div class="tab-content pl-lg-2">

                @foreach($variable_groups ?? [] as $name => $variables)
                    @php $name = $name ?? "no_name"; @endphp

                    <div class="tab-pane fade @if($loop->first) active show @endif" id="tab_{{$name}}"
                         role="tabpanel" aria-labelledby="tab_{{$name}}">

                        @foreach($variables as $variable)
                            <div class="col">
                                {!! Form::model($variable, ['route' => ["admin.variables.update", $variable->id],
                                                'method' => 'put', "enctype" => "multipart/form-data",'class' => 'w-100']) !!}
                                {!! Form::hidden('id', $variable->id) !!}
                                {!! Form::hidden('type', $variable->type??'') !!}
                                {!! Form::hidden('list', 1) !!}

                                <div
                                    class="card row w-100  {{$errors->has($variable->id.'.*') ? "border border-danger" : "border-top border-info"}}">
                                    <div class="card-body">

                                        <div class="col-12 d-flex justify-content-between">
                                            <label class="">{{$variable->name}}</label>

                                            <div class="form-group row ">
                                                <div class="btn-group btn-group-toggle ml-4" data-toggle="buttons">
                                                    <label class="btn btn-sm btn-outline-success">
                                                        <input type="radio" name="{{$variable->id.'[status]'}}"
                                                               autocomplete="off" value="1"
                                                        @if (old($variable->id.'.status', $variable->status ?? '') == "1") {{ 'checked' }} @endif>
                                                        <span class="mr-2"><i class="fas fa-eye"></i></span>
                                                        {{trans('admin_labels.status_on')}}
                                                    </label>
                                                    <label class="btn btn-sm btn-outline-secondary">
                                                        <input type="radio" name="{{$variable->id.'[status]'}}"
                                                               autocomplete="off" value="0"
                                                        @if (old($variable->id.'.status', $variable->status ?? '') == "0") {{ 'checked' }} @endif>
                                                        <span class="mr-2"><i class="fas fa-eye-slash"></i></span>
                                                        {{trans('admin_labels.status_off')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            @include("admin.view.variables.list._body", ['id' => $variable->id])
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex flex-row-reverse">
                                        @include('admin.partials.buttons._save', ['btn_class' => 'btn-sm'])
                                        {{--                                        {!! Form::submit(__("buttons.save"), ['class' => 'btn btn-success btn-md']) !!}--}}
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        @endforeach

                    </div>
                @endforeach

            </div>
        </div>

    </div>

@endsection
