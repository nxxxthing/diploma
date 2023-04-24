@php
    /**
     * @var string|int|null $id
     */
@endphp

@foreach (\App\Http\Middleware\LocaleMiddleware::$languages as $key => $locale)
    <li class="nav-item">
        <a class="{{($key == 0)?'nav-link active':'nav-link'}}"
           data-toggle="pill" href="#tab_{!! $locale !!}{{isset($id) ? '_' . $id : ''}}" role="tab"
           aria-controls="tab_{!! $locale !!}"
           aria-selected="true"
        >
            <i>{{country_flag(detect_locale($locale))}}</i>
            {{__('admin_labels.tab_'.$locale)}}
        </a>
    </li>
@endforeach
