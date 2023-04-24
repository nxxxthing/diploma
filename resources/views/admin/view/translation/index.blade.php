@extends('admin.layouts.master')

@section('content')
    <div class="row">
        @if ($has_conflicts)
            <a href="{{route('admin.translations.conflicts')}}" class="btn btn-danger">{{__('admin_labels.fix_conflicts')}}</a>
        @endif
        <div class="col-auto">
            <x-adminlte-button label="{{__('admin_labels.upload_translations')}}" data-toggle="modal" data-target="#excel_upload"  theme="btn btn-light-success"/>
            <form action="{{route('admin.translations.upload')}}" method="POST" enctype="multipart/form-data">
                <x-adminlte-modal id="excel_upload" title="{{__('admin_labels.upload_translations')}}" size="lg" theme="teal" icon="fas fa-check" v-centered static-backdrop scrollable>
                    @csrf
                    @include('admin.fields.file', [
                        'title' => 'file',
                        'model' => null,
                        'field' => 'file',
                        'remove_field' => 'isRemoveFile',
                        'accept' => '.xlsx,.xls,.csv',
                    ])
                    <x-slot name="footerSlot">
                        <x-adminlte-button class="ml-auto" theme="btn btn-light-success" label="{{__('admin_labels.upload')}}" type="submit"/>
                    </x-slot>
                </x-adminlte-modal>
            </form>
        </div>

        <div class="col-auto">
            <x-adminlte-button label="{{__('admin_labels.upload_translations_json')}}" data-toggle="modal" data-target="#json_upload"  theme="btn btn-light-success"/>
            <form action="{{route('admin.translations.upload')}}" method="POST" enctype="multipart/form-data">
                <x-adminlte-modal id="json_upload" title="{{__('admin_labels.upload_translations_json')}}" size="lg" theme="teal" icon="fas fa-check" v-centered static-backdrop scrollable>
                    @csrf
                    <input type="hidden" name="json" value="true">
                    <label for="locale">
                        {{__('admin_labels.locale')}}
                    </label>
                    <select class="form-control" name="locale">
                        @foreach($locales as $locale)
                            <option value="{{$locale}}">{{$locale}}</option>
                        @endforeach
                    </select>
                    @include('admin.fields.file', [
                        'title' => 'file',
                        'model' => null,
                        'field' => 'file',
                        'remove_field' => 'isRemoveFile',
                        'accept' => '.json,application/json',
                    ])
                    <x-slot name="footerSlot">
                        <x-adminlte-button class="ml-auto" theme="btn btn-light-success" label="{{__('admin_labels.upload')}}" type="submit"/>
                    </x-slot>
                </x-adminlte-modal>
            </form>
        </div>

        <div class="col-auto">
            <form action="{{ route('admin.translations.upload') }}" method="POST" onsubmit="answer = confirm('{{__('admin_labels.translation_will_override')}}'); if (answer) {activate_loader();} return answer;" style="display: inline-block;">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="sync" value="1">
                @csrf
                <input type="submit" class="btn btn-light-success" value="{{__('admin_labels.sync_with_google')}}">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="translations-table">

                        <form id="translations-change" action="{!! route('admin.translation.update', $group) !!}" method="post" role="form"
                              class="without-js-validation">


                            @include('admin.partials._buttons', ['class' => 'buttons-top'])
                            {!! csrf_field() !!}

                            <input type="hidden" name="page" value="{!! $page !!}">
                            <table class="table table-bordered table-striped">
                                <tbody>

                                <tr>
                                    <td colspan="{!! count($locales) + 1 !!}">
                                        <div class="text-center">
                                            {{--{!! $list->links() !!}--}}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="{!! count($locales) + 1 !!}" class="form-group">
                                        <form onsubmit="event.preventDefault()">
                                            <input type="text" size="40"
                                                   onkeydown="if (event.keyCode == 13) {
                                                       event.preventDefault(); showResult(this.value, true)
                                                   }"
                                                   onkeyup="if (event.keyCode == 8) {
                                                       let val = event.target.value;
                                                       if (val.length > 3) {
                                                            showResult(val)
                                                       } else {
                                                            showResult('');
                                                       }
                                                   } else if (event.keyCode == 13) {
                                                       showResult(this.value)
                                                   } else {
                                                       if (this.value.length > 2)
                                                       search(this.value)
                                                   }"
                                                   placeholder="{{__('admin_labels.search')}}">
                                            <div id="livesearch"></div>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 9%">{{__('admin_labels.key')}}</th>

                                    @foreach($locales as $locale)
                                        <th style="width: 13%">{!! trans('admin_labels.tab_' . $locale) !!}</th>
                                    @endforeach
                                </tr>
                                    @foreach($list as $key => $items)
                                        @include('admin.view.translation.partials.table', ['parent_key' => ''])
                                    @endforeach

                                <tr>
                                    <td colspan="{!! count($locales) + 1 !!}">
                                        <div class="text-center">
                                            {{--{!! $list->links() !!}--}}
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            @include('admin.partials._buttons', ['sticky' => true])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('input, select, textarea').on('change', function() {
            $(this).addClass('changed');
        });

        $('form#translations-change').on('submit', function() {
            $('input[type!="hidden"]:not(.changed), textarea:not(.changed)').prop('disabled', true);
        });
    });
    var list = <?php echo json_encode($list_for_search['data']); ?>;
    let timer = 0
    function search(str) {
        clearTimeout(timer);
        timer = setTimeout(function () {showResult(str, true)}, 500);
    }
    function find_and_expand(str, my_list) {
        let keys = Object.keys(my_list);

        keys.forEach(function (key) {
            if (key.toLowerCase().indexOf(str) === -1) {
                if (!check(str, key.replaceAll('.', '_')))
                    return;
            }
            let name = '';

            key.split('.').forEach(function (k) {
                if (name) {
                    name = name + '_' + k;
                } else {
                    name = k;
                }

                document.querySelectorAll('[data-parent=' + name + ']').forEach(function (key_d) {
                    key_d.removeAttribute('hidden');
                })
            });
        });
    }

    function showResult(str, after_enter = false, my_list = list['data']) {
        let keys = Object.keys(my_list);

        keys.forEach(function (key) {
            changeVisibility(key, str);

            if (key.includes('.')) {
                key.split('.').forEach(function (k) {
                    changeVisibility(k, str);
                });
            }
        });


        if (str.length > 2) {
            find_and_expand(str.toLowerCase(), my_list);
        } else if (after_enter) {
            alert("{{__('admin_labels.search_min_length')}}");
        }
    }

    function changeVisibility(key, str) {
        document.querySelectorAll('[id="' + key + "_td_"+'"]')
            .forEach(function (key_d) {
                if (str.length > 2) {
                    key_d.setAttribute('hidden', 'true');
                } else {
                    key_d.removeAttribute('hidden');
                }
            })

        let action = 'collapse';
        if (str.length > 2) {
            action = 'expand';
        }
        $('[id="' + key + "_card_"+'"]').CardWidget(action);
    }

    function check(str, name) {
        let item = document.querySelector('[data-parent=' + name + ']')
        return Array.from(item.querySelectorAll('textarea'))
            .filter(x => x.innerHTML.toLowerCase().indexOf(str) !== -1)
            .length !== 0
    }
</script>
@endsection
