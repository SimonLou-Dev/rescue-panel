<?php

namespace App\Http\Controllers\Dev;

use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Session;

class DashboardController extends Controller
{
    public function getService(){
        $this->authorize('dev');
        $MedicService = User::where('service', 'SAMS')->where('OnService', true)->get();
        $FireService = User::where('service', 'LSCoFD')->where('OnService', true)->get();


        return response()->json(['fire'=>$FireService, 'medic'=>$MedicService]);
    }

    public function getFiles(string $name){
        $this->authorize('dev');
        $files =  \File::allFiles(storage_path('logs'));
        if($name === 'all'){
            $finalFileData = [];
            foreach ($files as $file){
                if( $file->getFilename() != '.gitignore'){
                    $finalFileData[] = [
                        'name' => explode('.', $file->getFilename())[0],
                        'size' => self::formatBytes($file->getSize()),
                        'path' => $file->getPathname()
                    ];
                }

            }

            return response()->json(['files'=>$finalFileData]);
        }else{
            $selected = null;
            foreach ($files as $file){
                $ThisFileName = explode('.', $file->getFilename())[0];

                if($ThisFileName === $name) $selected = $file;
            }

            if(is_null($selected)){
                return  abort(404);
            }else{
                return response()->file($selected);
            }

        }
    }

    public function ninjaMod(string $id){
        $this->authorize('dev');
        if(!Auth::check()) return redirect()->route('login');
        $base = \App\Models\User::where('id', Auth::user()->id)->first();
        $newuser = \App\Models\User::where('id',$id)->first();
        if(!$base->dev) return redirect()->route('login');
        Session::forget('service');
        Session::forget('user');
        \Illuminate\Support\Facades\Auth::logout();
        \Illuminate\Support\Facades\Session::flush();
        Session::invalidate();
        Session::regenerateToken();
        Auth::login($newuser);
        Session::push('user', $newuser);
        Session::push('service', $newuser->service);
        return redirect()->route('dashboard');
    }





    public static function formatBytes($size, $precision = 1)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }


}
