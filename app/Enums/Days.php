<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;

enum Days: string
{
    use InvokableCases;
    use Options;

    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public static function formOptions(): array
    {
        $options = [];
        foreach (self::options() as $option) {
            $options[$option] = __("admin_labels.days.$option");
        }
        return $options;
    }
}
