<?php

namespace App\Http\Controllers\BlackCodes;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\BCList;
use App\Models\BCPersonnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonnelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public static function addPersonel(int $BCId, int $userId): \Illuminate\Http\JsonResponse
    {
        $bc = BCList::where('id', $BCId)->firstOrFail();
        $user = User::where('id', $userId)->first();
        $personnel = BCPersonnel::where('BC_id', $BCId)->where('user_id', Auth::user()->id)->get()->count();
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
        Notify::broadcast('Vous avez été affecté au BC #' . $bc->id,1, $user->id);
        return response()->json(['status'=>'OK'],201);
    }

    public function addPersonnlByName(Request $request){

    }


}
