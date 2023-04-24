@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-auto">
            <form action="{{ route('admin.translations.conflicts.force') }}" method="POST" onsubmit="answer = confirm('{{__('admin_labels.translations_will_stay_old')}}'); if (answer) {activate_loader();} return answer;" style="display: inline-block;">
                @csrf
                @method('POST')
                <input type="hidden" name="type" value="old">
                <input type="submit" class="btn btn-success" value="{{__('admin_labels.accept_old_translations')}}">
            </form>
            <form action="{{ route('admin.translations.conflicts.force') }}" method="POST" onsubmit="answer = confirm('{{__('admin_labels.translations_will_stay_new')}}'); if (answer) {activate_loader();} return answer;" style="display: inline-block;">
                @csrf
                @method('POST')
                <input type="hidden" name="type" value="new">
                <input type="submit" class="btn btn-primary" value="{{__('admin_labels.accept_new_translations')}}">
            </form>
        </div>
    </div>
    <form method="POST" action="{{route('admin.translations.conflicts.resolve')}}">
        @csrf
        @method('PUT')
        @foreach($translations as $locale => $items)
            <table class="table table-bordered table-striped">
                <tr>
                    <th> {{__('admin_labels.locale')}}</th>
                    <th colspan="3">{{$locale}}</th>
                </tr>
                <tr>
                    <th>{{__('admin_labels.key')}}</th>
                    <th>{{__('admin_labels.old_value')}}</th>
                    <th>{{__('admin_labels.new_value')}}</th>
                    <th style="min-width: 100px;">{{__('admin_labels.actions')}}</th>
                </tr>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->key}}</td>
                        <td>{{$item->value}}</td>
                        <td>{{$item->new_value}}</td>
                        <td style="min-width: 100px;">
                            <input type="radio" value="old" id="{{"{$item->key}_{$locale}_old"}}" name="{{"old[{$locale}][$item->key]"}}" hidden>
                            <input type="radio" value="new" id="{{"{$item->key}_{$locale}_new"}}" name="{{"new[{$locale}][$item->key]"}}" hidden>
                            <label for="{{"{$item->key}_{$locale}_old"}}" class="btn btn-xs btn-success translations-fix-button">{{__('admin_labels.accept_old')}}</label>
                            <label for="{{"{$item->key}_{$locale}_new"}}" class="btn btn-xs btn-primary translations-fix-button">{{__('admin_labels.accept_new')}}</label>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endforeach
    @include('admin.partials._buttons', ['sticky' => true])
    </form>
@endsection
