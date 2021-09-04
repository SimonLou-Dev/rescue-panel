<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Intervention;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;



class UserController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUser(Request $request): JsonResponse
    {
        $me= User::where('id', Auth::user()->id)->first();
        if(!is_null($request->query('orderBy')) && is_null($request->query('grade'))){
            $users = User::where('grade_id', '<=', $me->grade_id)->orderBy($request->query('orderBy'),$request->query('oderdir'))->get();
        }else{
            $users = User::where('grade_id', '<=', $me->grade_id)->orderBy($request->query('orderBy'),$request->query('oderdir'))->where('grade_id', $request->query('grade'))->get();
        }

        return response()->json([
            'status'=>'OK',
            'users'=>$users,
            'number'=>count($users)
        ]);
    }

    /**
     * @param string $user
     * @return JsonResponse
     */
    public function searchUser(string $user): JsonResponse
    {
        $users = User::where('name', 'like', $user . '%')->take(5)->get();
        return \response()->json(['status'=>'OK', 'users'=>$users],200);
    }

    /**
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     */
    public function changePilote(Request $request, string $user_id): JsonResponse
    {
        $user_id = (int) $user_id;
        $user = User::where('id', $user_id)->first();
        $user->pilote= !$user->pilote;
        $user->save();
        return \response()->json(['status'=>'OK'],201);
    }

    public function changeState(Request $request,string $user_id,string $state): JsonResponse
    {
        $user = User::where('id', (int) $user_id)->first();
        $user->serviceState = ($state == 'null' ? null: $state);
        $user->save();
        event(new Notify('Etat de service mis à jour',1));
        return response()->json(['status'=>'OK'],200);
    }

    public function setDiscordId(Request $request,string $discordid, string $id){
        $user = User::where('id', $id)->first();
        if($discordid ==null){
            $user->discord_id = null;
        }else{
            $user->discord_id = $discordid;
        }
        $user->save();
        return response()->json(['status'=>'OK'],200);
    }

}
