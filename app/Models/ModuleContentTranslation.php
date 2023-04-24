<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleContentTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'text',
    ];
}
