<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariableTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'variable_id',
        'content',
    ];
}
