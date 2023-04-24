<?php

namespace App\Http\Controllers\Traits;

use App\Facades\FileUploader;
use Illuminate\Database\Eloquent\Model;

trait FileProcessorTrait
{
    public function processFile(
        Model|null $model,
        $field,
        $remove_field,
        $module,
        $folder,
        $data,
    ) {
        if (($data[$remove_field] ?? false) && $model?->{$field}) {
            $this->removeFile($model, $field);
        }

        if (isset($data[$field]) && is_file($data[$field])) {
            $data[$field] = $this->storeFile($data, $field, $module, $folder);
        }

        return $data;
    }

    public function storeFile($data, $field, $module, $folder): false|string
    {
        return FileUploader::putAs(
            $data[$field],
            $folder,
            $module,
            pathinfo($data[$field]->getClientOriginalName(), PATHINFO_FILENAME)
        );
    }

    public function removeFile(Model|null $model, $field): bool
    {
        $operationStatusResult = false;

        if ($model?->{$field}) {
            $operationStatusResult = FileUploader::delete($model->{$field});
            $model->{$field} = null;
            $model->save();
        }

        return $operationStatusResult;
    }
}
