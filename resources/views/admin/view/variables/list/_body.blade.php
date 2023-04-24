@php
    /**
     * @var array $locales
     * @var string $locale
     * @var int $id variable->id
     * @var \App\Models\Variable $variable
     */
@endphp

@if ($errors->has($id.'.*'))
    <span style="color:red">
        @dump($errors->messages())
    </span>
@endif


<div class="mb-3">

    @if(!$variable->translatable)

        @include("admin.view.variables.list._field")

    @else

        <ul class="nav nav-tabs " role="tablist">

            @foreach ($locales as $locale)
                <li class="nav-item">
                    <a class="nav-link @if($loop->first) active @endif" data-toggle="pill"
                       href="#tab_{{$id}}_{{$locale}}" role="tab"
                       aria-controls="tab_{{$id}}_{{$locale}}"
                       aria-selected="true">
                        {{__("admin_labels.locales.$locale")}}
                        @if ($errors->has($variable->id.'.'.$locale.'.*')) <span class="ml-3 text-red">*</span> @endif
                    </a>
                </li>
            @endforeach

        </ul>

        <div class="tab-content pt-4 pl-2 pr-2">

            @foreach ($locales as $locale)
                <div class="tab-pane fade @if($loop->first) active show @endif"
                     id="tab_{{$id}}_{{$locale}}" role="tabpanel"
                     aria-labelledby="tab_{{$id}}_{{$locale}}">

                    <div class="form-group row">
                        @include("admin.view.variables.list._field", ['locale' => $locale])
                    </div>

                </div>
            @endforeach

        </div>
    @endif

</div>
