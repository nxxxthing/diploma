<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;

enum ProgressTypes: string
{
    use InvokableCases;
    use Options;

    case TEXT = 'text';
    case IMAGE = 'image';
    case FILE = 'file';

    public static function formOptions(): array
    {
        $options = [];
        foreach (self::options() as $option) {
            $options[$option] = __("admin_labels.progress_types.$option");
        }
        return $options;
    }
}
