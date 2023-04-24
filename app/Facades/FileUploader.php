<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method put($file, $type, $module)
 * @method putAs($file, $type, $module, $filename = null)
 * @method saveSvgContent($text, $type, $module)
 * @method move(string $from, string $to)
 * @method delete(string $path)
 * @method _preparePath($root = null)
 * @method url(string $path)
 * @method exists(string $path)
 * @method setRoot(string $root)
 * @method makePdfPreview(string $url) : string {
 * @method preparePathFromString($string)
 * @method getFileTypeFromString($string): string
 * @method prepareUrlFile($file)
 *
 * @see \App\Classes\FileUploader
 */
class FileUploader extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fileUploader';
    }
}
