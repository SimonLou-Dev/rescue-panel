<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function getFiles(){
        $this->authorize('dev');
        $finalFileData = [];
        $files =  \File::allFiles(storage_path('app/public/logs'));
        foreach ($files as $file){
            if( $file->getFilename() != '.gitignore'){
                $finalFileData[] = [
                    'name' => explode('.', $file->getFilename())[0],
                    'size' => DashboardController::formatBytes($file->getSize()),
                    'creation' =>date('d/m/Y H:i', $file->getCTime())
                ];
            }
        }
        return response()->json([
            'files'=>$finalFileData
        ]);
    }

    public function deleteFile(string $name){
        $this->authorize('dev');
        $selected = null;
        $files =  \File::allFiles(storage_path('app/public/logs'));
        foreach ($files as $file){
            if(explode('.', $file->getFilename())[0] === $name)$selected = $file;
        }
        if(!is_null($selected)){
            \File::delete($selected->getPathname());
        }

        return response()->json([],202);

    }
}
