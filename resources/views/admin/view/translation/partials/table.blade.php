
@foreach($items as $key => $items)
    @if (is_array($items))
        <tr id="{{$key}}_td_" data-parent="{{$parent_key . ($parent_key ? '_' : '') . $key}}">
            <td colspan="{{1 + sizeof(config('app.locales'))}}">
                <x-adminlte-card :title="$key" theme="purple" icon="fas fa-lg fa-fan" collapsible="collapsed" id="{{$key}}_card_" header-class="clickable-card" maximizable>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 9%">{{__('admin_labels.key')}}</th>

                            @foreach($locales as $locale)
                                <th style="width: 13%">{!! trans('admin_labels.tab_' . $locale) !!}</th>
                            @endforeach
                        </tr>
                        @include('admin.view.translation.partials.table',
                            ['parent_key' => $parent_key . ($parent_key ? '_' : '') . $key])
                    </table>
                </x-adminlte-card>
            </td>
        </tr>
    @else

        <tr id="{{$key}}_td_" data-parent="{{$parent_key . ($parent_key ? '_' : '') . $key}}">
            <td>
                {{$key}}
            </td>
            @foreach($locales as $locale)
                <td class="form-group
                    @if ($errors->has($locale.'.'.$items->keys()[0])) has-error @endif">
                    <textarea rows="1" cols="1"
                              name="{!! $locale !!}[{!! $items->keys()[0] !!}]"
                              id="{!! $locale !!}_{!! str_replace(' ', '_', $items->keys()[0]) !!}"
                              class="form-control input-sm"
                    >{!! $items->first()[$locale] ?? null!!} </textarea>
                </td>
            @endforeach
        </tr>
    @endif
@endforeach

