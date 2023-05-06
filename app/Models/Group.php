<?php

namespace App\Models;

use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use WithTranslationsTrait;
    use Translatable;

    protected $translatedAttributes = [
        'title'
    ];

    protected $fillable = [
        'cathedra_id',
    ];

    public function cathedra()
    {
        return $this->belongsTo(Cathedra::class);
    }
}
