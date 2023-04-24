<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Resources;

use App\Models\Traits\Includeble;
use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Role
 */
class RoleResource extends JsonResource
{
    use Includeble;

    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'slug'  => $this->slug,
        ];
    }
}
