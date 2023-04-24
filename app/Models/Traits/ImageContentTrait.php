<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageContentTrait
{
    public function getImageContent(): bool|string
    {
        return Storage::get($this->image);
    }
}
