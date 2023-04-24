<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait AjaxReorderTrait
{
    public function reorder(Request $request)
    {
        $class_name = get_model_by_controller(__CLASS__);
        $class = '\App\Models\\' . $class_name;
        $data = $request->get('rows', []);

        $data = collect($data)->map(function ($item) {
            return ['id' => $item['id'], 'position' => $item['position']];
        })->toArray();

        $class::upsert($data, ['id']);

        return response()->noContent();
    }
}
