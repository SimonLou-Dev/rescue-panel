<?php

namespace App\Http\Controllers\BlackCodes;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Models\BCList;
use App\Models\BCPersonnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonnelController extends Controller
{

    public static function addPersonel(string $BCId, string $userId): \Illuminate\Http\JsonResponse
    {

        \Gate::authorize('ModifyPatient', BCList::class);
        $BCId = (int) $BCId;
        if(is_numeric($userId)){
            $userId = (int) $userId;
            $user = User::where('id', $userId)->first();
        }else{
            $user = User::where('name', $userId)->first();
            $userId = (int) $user->id;
        }
        $bc = BCList::where('id', $BCId)->firstOrFail();
        $userId = (int) $userId;


        $personnel = BCPersonnel::where('BC_id', $BCId)->where('user_id', $userId)->get()->count();
        if($personnel == 0){
            $personnel = new BCPersonnel();
            $personnel->user_id = $user->id;
            $personnel->name = $user->name;
            $personnel->service = ($user->medic ? 'SAMS' : 'LSCoFD');
            $personnel->BC_id = $bc->id;
            $personnel->save();
        }

        $user->bc_id = $bc->id;
        $user->save();
        if($user->id != Auth::user()->id){
            Notify::broadcast('Vous avez affecté ' . $user->name,1, Auth::user()->id);
        }
        $logs = new LogsController();
        $logs->BCLogging('join', $bc->id, Auth::user()->id);
        Notify::broadcast('Vous avez été affecté au BC #' . $bc->id,1, $user->id);
        return response()->json(['status'=>'OK'],201);
    }

}
