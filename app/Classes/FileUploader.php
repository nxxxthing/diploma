<?php

declare(strict_types=1);

namespace App\Classes;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploader
{
    protected $root = 'uploads';

    public function put($file, $type, $module)
    {
        $path = $this->_preparePath($this->root . '/' . $type . '/' . $module);

        return Storage::putFile($path, new File($file->getPathname()));
    }

    public function putAs($file, $type, $module, $filename = null)
    {
        $path = $this->_preparePath($this->root . '/' . $type . '/' . $module);

        return Storage::putFileAs($path, new File($file->getPathname()), $filename . '.' . $file->getClientOriginalExtension());
    }

    public function saveSvgContent($text, $type, $module)
    {
        if ($text == '') {
            return null;
        }

        $path = $this->_preparePath($this->root . '/' . $type . '/' . $module) . '/' . Str::random() . '.svg' ;

        Storage::put($path, $text);

        return $path;
    }

    public function move(string $from, string $to)
    {
        $from = $this->_prepareToMove($from);

        $to = $this->_prepareToMove($to);

        return Storage::move($from, $to);
    }

    public function delete(string $path)
    {
        return Storage::delete($path);
    }

    public function _preparePath($root = null)
    {
        $name = Str::lower(Str::random(32));

        $root = empty($root) ? $this->root : $root;

        $a = substr($name, 0, 2);
        $b = substr($name, 2, 2);

        return $root . '/' . $a . '/' . $b;
    }

    public function url(string $path)
    {
        return Storage::url($path);
    }

    public function exists(string $path)
    {
        return Storage::exists($path);
    }

    public function setRoot(string $root)
    {
        $this->root = $root;
    }

    private function _prepareToMove($link)
    {
        $link = explode('/', $link);
        $link = array_splice($link, 4);
        $link = implode('/', $link);

        return $link;
    }

    public function makePdfPreview(string $url): string
    {
        $file_name = substr(substr($url, strrpos($url, '/') + 1), 0, -4);

        $path = base_path('public/storage/images/previews/');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $imagick = new \Imagick();

        $imagick->readImage($url . '[0]');

        if (!file_exists($path . $file_name . '.jpg')) {
            $imagick->writeImages($path . $file_name . '.jpg', false);
        }

        return '/storage/images/previews/' . $file_name . '.jpg';
    }

    /**
     * @param $string
     * @return string|string[]|null
     */
    public function preparePathFromString($string)
    {
        return preg_replace('/.+storage\//', '', $string);
    }

    public function getFileTypeFromString($string): string
    {
        return  in_array(substr($string, strrpos($string, '.') + 1), ['jpeg', 'png', 'gif', 'jpg']) ? 'images' : 'files';
    }

    public function prepareUrlFile($file)
    {
        if (str_contains($file, config('app.url'))) {
            $file = $this->preparePathFromString($file);
        }

        return $file;
    }
}
