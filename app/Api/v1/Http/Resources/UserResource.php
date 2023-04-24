<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Resources;

use App\Models\Traits\Includeble;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    use Includeble;

    public function toArray($request)
    {
        return [
            'id'        => (int)$this->id,
            'name'      => $this->name,
            'email'     => $this->email,

            'role'    => $this->when(
                $this->hasInclude('role'),
                ['data' => RoleResource::make($this->roles()->first())]
            )
        ];
    }
}
