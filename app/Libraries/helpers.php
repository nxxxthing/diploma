<?php

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Encryption\Encrypter;

if (!function_exists('get_model_by_controller')) {
    /**
     * @param $class
     *
     * @return string
     */
    function get_model_by_controller($class)
    {
        $class = explode('\\', str_replace('Controller', '', $class));

        return array_pop($class);
    }
}

if (!function_exists('theme_asset')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function theme_asset($path = '')
    {
        return $path ? Theme::asset($path) : '';
    }
}

if (!function_exists('thumb')) {
    /**
     * @param string   $path
     * @param int      $width
     * @param int|null $height
     *
     * @return string
     *
     */
    function thumb($path = '', $width = null, $height = null)
    {
        $thumb = null;

        if (URL::isValidUrl($path)) {
            return $path;
        }

        $height = $height ? : $width;
        $path = File::exists(public_path($path)) ? $path : null;

        if ($path) {
            if (!$width) {
                $img_info = getimagesize(public_path($path));

                $width = $img_info[0];
                $height = $img_info[1];
            } elseif ($width && $height) {
                $img_info = getimagesize(public_path($path));

                if (!empty($img_info)) {
                    $width = $width <= $img_info[0] ? $width : $img_info[0];
                    $height = $height <= $img_info[1] ? $height : $img_info[1];
                }
            }

            $thumb = url(Thumb::thumb(public_path($path), $width, $height)->link());
        }

        return $thumb ? : '';
    }
}

if (!function_exists('variable')) {
    /**
     * Get / set the specified variable value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string $key
     * @param  mixed        $default
     *
     * @return mixed
     */
    function variable($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('variable');
        }

        return app('variable')->get($key, $default);
    }
}

if (!function_exists('is_admin_panel')) {
    /**
     * @return bool
     */
    function is_admin_panel()
    {
        if (php_sapi_name() == 'cli') {
            return false;
        }

        return request()->segment(1) == '9a654138bc7c1c48fba1d0b5ca526a28';
    }
}

if (!function_exists('lang_rules')) {
    /**
     * @param array $rules
     * @param array|null $locales
     * @return array
     */
    function lang_rules(array $rules, array $locales = null): array
    {
        return RuleFactory::make($rules, null, null, null, $locales);
    }
}

if (!function_exists('carbon')) {
    /**
     * @return \Carbon\Carbon
     */
    function carbon()
    {
        return new Carbon\Carbon();
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = '';

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));

                $headers[$key] = $value;
            }
        }

        return $headers;
    }
}

if(!function_exists('detect_locale')) {
    function detect_locale($locale): string
    {
        if ($locale == 'en') {
            return 'us';
        }

        if ($locale == 'uk') {
            return 'ua';
        }

        return $locale;
    }
}

if (!function_exists('file_url')) {

    function file_url($string, $favicon = false): ?string
    {
        if ($favicon) {
            return config('app.url') . '/' . str_replace('public', '', $string);
        }

        if ($string) {
            if (!str_contains($string, 'http')) {
                return str_contains($string, 'storage/') ? asset($string) : asset('storage/' . $string);
            }

            return $string;
        }

        return null;
    }
}

if (!function_exists('generateAdminHash')) {
    function generateAdminHash(): ?string
    {
        $key = config('app.verify_key');
        $cipher = config('app.code_cipher');

        $encrypter = new Encrypter($key, $cipher);

        return $encrypter->encrypt(config('app.name'), false);
    }
}

if (!function_exists('drand')) {
    function drand(float $min, float $max, int $digits = 1): float
    {
        $scale = pow(10, $digits);
        return mt_rand((int) ($min * $scale), (int) ($max * $scale)) / $scale;
    }
}
