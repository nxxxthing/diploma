<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploaderController extends Controller
{

    /**
     * @param Request $request
     * @return array
     * @throws \Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException
     */
    public function uploadVideo(Request $request): array
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived && $fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            abort_unless(\Gate::allows($request->get('module') . '_' . $request->get('ability')), 403);

            $file = $fileReceived->getFile(); // get file

            $type = $request->get('type', 'unknown');
            $module = $request->get('module', 'general');
            $locale = $request->get('locale');

            $extension = $file->getClientOriginalExtension();

            $path = 'video/' . $module;
            $path .= empty($type) ? '' : '/' . $type;

            if ($locale) {
                $path .= '/' . $locale;
            }

            $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()) //file name without extension
                . '_' . md5(time()) . '.' . $extension; // a unique file name

            $path = Storage::disk('public')->putFileAs($path, $file, $fileName);

            // delete chunked file
            unlink($file->getPathname());

            return [
                'path' => $path,
                'filename' => $fileName,
                'url' => Storage::disk('public')->url($path),
            ];
        }

        // otherwise, return percentage information
        $handler = $fileReceived->handler();

        return [
            'done' => $handler ? $handler->getPercentageDone() : false,
            'status' => true,
        ];
    }
}
