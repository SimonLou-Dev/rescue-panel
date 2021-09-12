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
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;


class UserController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUser(Request $request): JsonResponse
    {
        $me = User::where('id', Auth::user()->id)->first();
        if (!is_null($request->query('orderBy')) && is_null($request->query('grade'))) {
            $users = User::where('grade_id', '<=', $me->grade_id)->orderBy($request->query('orderBy'), $request->query('oderdir'))->get();
        } else {
            $users = User::where('grade_id', '<=', $me->grade_id)->orderBy($request->query('orderBy'), $request->query('oderdir'))->where('grade_id', $request->query('grade'))->get();
        }

        return response()->json([
            'status' => 'OK',
            'users' => $users,
            'number' => count($users)
        ]);
    }

    /**
     * @param string $user
     * @return JsonResponse
     */
    public function searchUser(string $user): JsonResponse
    {
        $users = User::where('name', 'like', $user . '%')->take(5)->get();
        return \response()->json(['status' => 'OK', 'users' => $users], 200);
    }

    /**
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     */
    public function changePilote(Request $request, string $user_id): JsonResponse
    {
        $user_id = (int)$user_id;
        $user = User::where('id', $user_id)->first();
        $user->pilote = !$user->pilote;
        $user->save();
        return \response()->json(['status' => 'OK'], 201);
    }

    public function changeState(Request $request, string $user_id, string $state): JsonResponse
    {
        $user = User::where('id', (int)$user_id)->first();
        $user->serviceState = ($state == 'null' ? null : $state);
        $user->save();
        event(new Notify('Etat de service mis à jour', 1));
        return response()->json(['status' => 'OK'], 200);
    }

    public function setDiscordId(Request $request, string $discordid, string $id): JsonResponse
    {
        $user = User::where('id', $id)->first();
        if ($discordid == null) {
            $user->discord_id = null;
        } else {
            $user->discord_id = $discordid;
        }
        $user->save();
        return response()->json(['status' => 'OK'], 200);
    }

    public function getUserNote(string $user_id): JsonResponse
    {
        $user = User::where('id', $user_id)->first();
        return \response()->json([
            'status'=> 'ok',
            'note'=> $user->note
        ]);
    }

    public function getUserSanctions(string $user_id): JsonResponse
    {
        $user = User::where('id', $user_id)->first();
        return \response()->json([
            'status'=> 'ok',
            'sanctions'=> $user->sanctions
        ]);
    }

    public function getUserInfos(string $user_id): JsonResponse
    {
        $user = User::where('id', $user_id)->first();
        $user->GetGrade;
        return \response()->json([
            'status'=> 'ok',
            'infos'=> $user
        ]);
    }

    public function getUserMaterial(string $user_id): JsonResponse
    {
        $user = User::where('id', $user_id)->first();
        $base = (array) json_decode($user->materiel);

        return \response()->json([
            'status'=> 'ok',
            'material'=>(array) $base
        ]);
    }

    public function addUserNote(Request $request, string $id)
    {

    }

    public function removeUserNote(Request $request, string $id, string $note_id)
    {

    }

    public function addUserSanction(Request $request, string $id)
    {

    }

    public function ModifyUserMaterial(Request $request, string $id)
    {



        $user = User::where('id',  $id)->first();
        $base = (array) json_decode($user->materiel);
        $user->materiel = json_encode((array) $request->get('material'));
        $user->save();

        if(!is_null($base)){
            $title = 'Chagement de matériel attribué';
        }else{
            $title = 'Atribution de matériel';
        }
        $matos= (array) $request->get('material');
        $keys = (array) array_keys($matos);
        $attribute = "";
        for($a = 0; $a < count($matos);$a++){
            if($matos[$keys[$a]]){
                $attribute .= (empty($attribute) ? $keys[$a] : ', '.$keys[$a]);
            }
        }



        Http::post(env('WEBHOOK_LOGISTIQUE'),[
            'username'=> "BCFD - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                [
                    'title'=>$title,
                    'color'=>'752251',
                    "thumbnail"=> [
                        "url"=> "https://media.discordapp.net/attachments/772181497515737149/846364157821321256/bodyArmor-bulletproof-kevlar-vest-512.png"
                    ],
                    'fields'=>[
                        [
                            'name'=>'Matricule : ',
                            'value'=>($user->matricule != null ? $user->matricule : 'non définie'),
                            'inline'=>false
                        ],[
                            'name'=>'Prénom nom : ',
                            'value'=>$user->name,
                            'inline'=>false
                        ],[
                            'name'=>'Item attribués : ',
                            'value'=>$attribute,
                            'inline'=>false
                        ],[
                            'name'=>'email : ',
                            'value'=>($user->discord_id != null ? '<@'.$user->discord_id.'>' : 'non définie'),
                            'inline'=>false
                        ]
                    ],
                ]
            ],

        ]);


        return \response()->json([
            'status'=>'ok',
        ]);
    }

    public function userQuitService(Request $request, string $id)
    {

    }

}
