<?php

namespace App\Http\Controllers\Dev;

use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Session;

class ViewController extends Controller
{
    public function getDashboard(){
        $MedicService = User::where('service', 'SAMS')->where('OnService', true)->get();
        $FireService = User::where('service', 'LSCoFD')->where('OnService', true)->get();

        $finalFileData = [];

        $files =  \File::allFiles(storage_path('logs'));
        foreach ($files as $file){
            $finalFileData[] = [
                'name' => explode('.', $file->getFilename())[0],
                'size' => self::formatBytes($file->getSize()),
                'path' => $file->getPathname()
            ];
        }


        return view('dev.dashboard', ['medicService'=>$MedicService, 'fireService'=>$FireService, 'files'=>$finalFileData]);
    }

    public function getMdt(){
        $user = User::where('id',Auth::user()->id)->first();
        $userSrv = $user->OnService;
        $user->service = 'SAMS';
        Session::forget('service');
        Session::push('service','SAMS');
        $user->save();
        UserUpdated::dispatch($user);
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

    public function getUser(){
        $users = User::all();
        $medicGrade = Grade::where('service', 'SAMS')->orWhere('service', 'staff')->orWhere('service', 'dev')->get();
        $fireGrade = Grade::where('service', 'LSCoFD')->orWhere('service', 'staff')->orWhere('service', 'dev')->get();

        return view('dev.users', ['users'=>$users, 'medicGrade'=>$medicGrade, 'firegrade'=>$fireGrade]);
    }
}
