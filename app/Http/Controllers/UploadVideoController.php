<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Support\Facades\Storage;


class UploadVideoController extends Controller
{
    public function index() {
        return view('upload.index');
    }
    public function upload(Request $request) {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if (!$receiver->isUploaded()) {
            // file not uploaded
        }
        $fileReceived = $receiver->receive();
        if($fileReceived->isFinished()) {
            $file =  $fileReceived->getFile();
            $extension = explode('/',$request->resumableType)[1];
            $name = str_replace('.'.$extension, '', $file->getClientOriginalName());
            $name = md5(time()) .'.'. $extension;
            $disk = \Storage::disk('public');
            $path = $disk->putFileAs('video',$file,$name);

            unlink($file->getPathName());
            return [
                'path' => asset('storage/'.$path),
                'name' => $name,
            ];
        }
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true,
        ];
    }
}
