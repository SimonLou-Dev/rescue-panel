<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use function PHPUnit\Framework\directoryExists;


class FileController extends Controller
{
    /**
     * @var string
     */
    private string $laravelTempDir;

    public function __construct()
    {
        $this->laravelTempDir = public_path('/storage/temp_upload/');
    }

    public function uploadFile(Request $request)
    {
        $file = $request->file('file');
        if(!isset($file) || !isset($request->id)){
            return response()->json(['status'=>'error'],500);
        }

        $tempFile = $this->laravelTempDir  . $request->id . '_' . explode('/', $file->getClientMimeType())[1] . '.temp';

        file_put_contents($tempFile, $file->getContent(), FILE_APPEND);

        return response()->json(['status'=>'OK'],200);
    }



    /*
     * $receiver = new FileReceiver("files", $request, HandlerFactory::classFromRequest($request));
        return response()->json([$receiver],500);
        if($receiver->isUploaded() === false){
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();
        return response()->json([$save],500);
        if($save->isFinished()){
            return $this->saveFile($save->getFile());
        }

        $handler = $save->handler();
        return response()->json([
           "done"=>$handler->getPercentageDone(),
        ]);
     */

    /**
     * @param object $filemetadata
     * @return string
     */
    private function endOffUpload(string $id){

    }

    public static function moveTempFile(string $dir){
        //make
    }

    public static function deletTempFile(string $dir){
        //make
    }




}
