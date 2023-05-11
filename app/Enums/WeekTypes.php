<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;

enum WeekTypes: string
{
    use InvokableCases;
    use Options;

    case FIRST = 'first';
    case SECOND = 'second';

    public static function formOptions(): array
    {
        $options = [];
        foreach (self::options() as $option) {
            $options[$option] = __("admin_labels.week_types.$option");
        }
        return $options;
    }
}
