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

    public function addPersonel(string $id): \Illuminate\Http\JsonResponse
    {
        $id = (int) $id;
        $bc = BCList::where('id', $id)->firstOrFail();
        $personnel = BCPersonnel::where('BC_id', $id)->where('user_id', Auth::user()->id)->get()->count();
        if($personnel == 0){
            $personnel = new BCPersonnel();
            $personnel->user_id = Auth::user()->id;
            $personnel->name = Auth::user()->name;
            $personnel->BC_id = $bc->id;
            $personnel->save();
        }
        $user = User::where('id', Auth::user()->id)->first();
        $user->bc_id = $bc->id;
        $user->save();
        event(new Notify('Vous avez été affecté à ce BC ! ',1));
        return response()->json(['status'=>'OK'],201);
    }

    public function removePersonnel(int $id): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->bc_id = null;
        $user->save();
        event(new Notify('Vous avez été désaffecté de ce BC ! ',1));
        return response()->json(['status'=>'OK'],202);
    }
}
