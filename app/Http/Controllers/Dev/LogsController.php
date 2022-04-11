<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function streamFile(string $name){
        $files =  \File::allFiles(storage_path('logs'));
        $selected = null;
        foreach ($files as $file){
            if($name === explode('.', $file->getFilename())[0]) $selected = $file;
        }

        if(is_null($selected)) return redirect()->route('dev.dashboard');

        return view('dev.logsFile', ['file'=>\File::get($selected->getRealPath())]);

    }
}
