<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdminSetting extends Model
{
    protected $fillable = [
        'group',
        'status',
        'key',
        'value',
        'pure_value',
        'replace_template',
        'default',
        'default_color'
    ];

    public static $colors = [
        'primary'   => '#007bff',
        'secondary' => '#6c757d',
        'info'      => '#17a2b8',
        'success'   => '#28a745',
        'warning'   => '#ffc107',
        'danger'    => '#dc3545',
        'black'     => '#000000',
        'gray-dark' => '#343a40',
        'gray'      => '#adb5bd',
        'light'     => '#1f2d3d',
        'indigo'    => '#6610f2',
        'lightblue' => '#3c8dbc',
        'navy'      => '#001f3f',
        'purple'    => '#605ca8',
        'fuchsia'   => '#f012be',
        'pink'      => '#e83e8c',
        'maroon'    => '#d81b60',
        'orange'    => '#ff851b',
        'lime'      => '#01ff70',
        'teal'      => '#39cccc',
        'olive'     => '#3d9970',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::saveCache();
        });
    }

    public static function saveCache()
    {
        $settings = AdminSetting::get();
        $data = [];

        foreach ($settings as $setting) {
            $data[] = [
                'group' => $setting->group,
                'key'   => $setting->key,
                'value' => $setting->value
            ];
        }

        Cache::forever('admin_settings', $data);

        return $data;
    }

    public static function getColorKeyByValue($value)
    {
        return array_search($value, self::$colors);
    }
}
