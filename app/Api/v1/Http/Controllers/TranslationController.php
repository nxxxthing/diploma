<?php

namespace App\Api\v1\Http\Controllers;

use App\Models\Translation;
use Illuminate\Support\Arr;

class TranslationController extends BaseController
{
    public function __invoke(): ?array
    {
        $locale = app()->getLocale();
        $group = 'site_labels';

        $path = app()->langPath() . '/' . $locale . '/' . $group . '.php';
        $_file_list = Arr::dot(include($path));

        $_translation = Translation::whereLocale($locale)->whereGroup($group)
            ->get(['key', 'value'])
            ->keyBy('key');

        foreach ($_file_list as $key => $item) {
            $list[$key] = $_translation->has($key) ? $_translation->get($key)->value : $item;
        }

        $_db_list = Arr::except($_translation->toArray(), array_keys($_file_list));

        foreach ($_db_list as $key => $item) {
            $list[$key] = $item['value'];
        }

        return $list ?? null;
    }
}
