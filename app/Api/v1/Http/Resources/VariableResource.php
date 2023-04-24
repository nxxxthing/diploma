<?php

namespace App\Api\v1\Http\Resources;

use App\Models\Variable;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Variable */
class VariableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'key'  => $this->key,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'translatable' => $this->translatable,
            'group' => $this->group,
            'in_group_position' => $this->in_group_position,
            'value' => $this->translatable ? $this->content : $this->value,
            'status' => $this->status,
        ];
    }
}
