<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Storage;

trait AjaxFieldsChangerTrait
{
    public function ajaxFieldChange($id)
    {
        $class_name = get_model_by_controller(__CLASS__);
        $class = '\App\Models\\' . $class_name;

        $model = new $class();

        $model = $model::find($id);

        if ($model) {
            $field = request('field', null);
            $value = request('value', null);

            if (!empty($field)) {
                switch ($field) {
                    case 'promo_video':
                        Storage::delete($model->promo_video);
                        $model->update(['promo_video' => null]);
                        break;
                    default:
                        $model->update([$field => $value]);
                }
            }
        }
    }
}
