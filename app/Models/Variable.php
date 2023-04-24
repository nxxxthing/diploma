<?php

namespace App\Models;

use App\Models\Traits\VisibleTrait;
use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin VariableTranslation
 */
class Variable extends Model
{
    use Translatable;
    use WithTranslationsTrait;
    use VisibleTrait;

    protected $translatedAttributes = [
        'content'
    ];

    public const type_TITLE = 'title'; // short text /input - type=text
    public const type_TEXT  = 'text';  // long text /textarea
    public const type_IMAGE = 'image'; // image /input - file select
    public const type_FILE = 'file';   // image /input - file select

    public const types = [
        self::type_TITLE,
        self::type_TEXT,
        self::type_IMAGE,
        self::type_FILE,
    ];

    protected $fillable = [
        'key',
        'type',
        'name',
        'description',
        'translatable',
        'group',
        'in_group_position',
        'value',
        'status',
    ];
}
