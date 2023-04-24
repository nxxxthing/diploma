<?php

namespace App\Facades;

use App\Models\Role;
use Illuminate\Support\Facades\Facade;

/**
 * Class Thumb
 * @method for(Role ...$role): static
 * @method except(array $except): static
 * @method createModule($slug, $translations): static
 * @method syncModule(): static
 * @method clear(): static
 * @method clearPermissions(): static
 * @method withAdditional($permissions): static
 *
 * @see \App\Classes\Permissions
 * @package App\Facades
 */
class Permissions extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'permissions';
    }
}
