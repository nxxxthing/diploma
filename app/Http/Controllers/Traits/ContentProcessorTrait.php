<?php

namespace App\Http\Controllers\Traits;

use App\Facades\FileUploader;
use Illuminate\Database\Eloquent\Model;

trait ContentProcessorTrait
{
    use FileProcessorTrait;

    public function processContent(
        Model $model,
              $field,                  //contents
              $relation,               // content,
              $module,
              array $need_fields,      // ['image', 'text', title, 'video'];
              array|null $files        // [['filed' => 'image', 'remove_field' => 'isRemoveImage', 'folder' => 'image']]
    ) {
        $contents_ids = $model->{$relation}->pluck('id');

        $contents_old = request("$field.old", []);

        $contents_old_key = array_keys($contents_old);

        foreach ($contents_ids as $item) {
            if (!in_array($item, $contents_old_key)) {
                $model->{$relation}()->where('id', $item)->delete();
            }
        }

        foreach ($contents_old as $key => $content) {
            if ($this->contentExists($content, $need_fields)) {
                $exist_module = $model->{$relation}()->where('id', $key)->first();
                foreach ($files as $file) {
                    $content = $this->processFile(
                        model: $exist_module,
                        field: $file['field'],
                        remove_field: $file['remove_field'],
                        module: $module,
                        folder: $file['folder'],
                        data: $content,
                    );
                }

                $exist_module->update($content);
            }
        }

        $contents = request("$field.new", []);

        unset($contents['replaseme']);

        foreach ($contents as $content) {
            if ($this->contentExists($content, $need_fields)) {
                foreach ($files as $file) {
                    $content = $this->processFile(
                        model: null,
                        field: $file['field'],
                        remove_field: $file['remove_field'],
                        module: $module,
                        folder: $file['folder'],
                        data: $content,
                    );
                }
                $model->{$relation}()->create($content);
            }
        }
    }

    private function contentExists($data, $searchKeys)
    {
        $result = $this->getExistsValue($data);

        foreach ($searchKeys as $key) {
            if (in_array($key, $result)) {
                return true;
            }
        }

        return false;
    }

    private function getExistsValue($data, $searchKey = null)
    {
        $result = [];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $result = array_merge($result, $this->getExistsValue($value, $key));
            }
        } elseif ($data) {
            $result = [$searchKey];
        }

        return $result;
    }
}
