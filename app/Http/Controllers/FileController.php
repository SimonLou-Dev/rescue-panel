<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class FileController extends Controller
{
    public function uploadFile(Request $request): \Illuminate\Http\JsonResponse
    {

        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        if($receiver->isUploaded() === false){
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();
        if($save->isFinished()){
            return $this->saveFile($save->getFile());
        }

        $handler = $save->handler();
        return response()->json([
           "done"=>$handler->getPercentageDone(),
        ]);
    }

    protected function saveFile(UploadedFile $file, int $type)
    {
        $fileName = $this->createFilename($file);
        $mime = str_replace('/', '-', $file->getMimeType());
        $finalPath = storage_path("temp_upload/");
        $file->move($finalPath, $fileName);

        return response()->json([
            'path' => $finalPath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }

    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }

    public static function moveTempFile(){
        //make
    }

    public static function deletTempFile(){
        //make
    }




}
