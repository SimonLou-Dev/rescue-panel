<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\Grade;
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
    public function register(Request  $request): JsonResponse
    {
        $pseudo = $request->pseudo;
        $mail = $request->email;
        $psw = $request->psw;
        if($user = User::where('email', $mail)->count() != 0){
            return response()->json([
                'status' => 'ERROR',
                'raison'=> 'Email taken',
                'datas' => []
            ], 200);
        }else{
            $createuser = new User();
            $createuser->name = $pseudo;
            $createuser->email = $mail;
            $createuser->password = Hash::make($psw);
            $createuser->save();
            $newuser = User::where('email', $mail)->first();
            Auth::login($newuser);
            Session::push('user_grade', $newuser->GetGrade);
            if(Auth::check()){
                return response()->json([
                    'status' => 'OK',
                    'datas' => [
                        'user' => $newuser,
                        'authed' => true,
                    ]
                ], 201);
            }else{
                return response()->json([
                   'status' => 'error',
                   'user' => null,
                   'authed' => false,
                    'check' => Auth::check(),
                ], 200);
            }
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $email = $request->email;
        $psw = $request->psw;
        if(User::where('email', $email)->count() == 0){
            $returned = response()->json([
                'status'=> 'adresse mail non existante',
                'user' => null,
                'authed' => false,
            ], 202);
        }else{
            $user= User::where('email', $email)->first();
             if(Hash::check($psw, $user->password)){
                Auth::login($user);
                 Session::push('user_grade', $user->GetGrade);
                    if($user->liveplace != null){
                        $returned = response()->json([
                            'status'=>'OK',
                            'user'=>$user,
                            'authed'=>Auth::check(),
                        ]);
                    }else{
                        $returned = response()->json([
                            'status'=>'INFOS',
                            'user'=>$user,
                            'authed'=>Auth::check(),
                        ]);
                    }
            }else{
                $returned = response()->json([
                    'status'=>'Mot de passe invalide',
                    'user' => null,
                    'authed'=>false
                ], 202);
            }
        }
        return $returned;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUser(Request $request): JsonResponse
    {
        $me= User::where('id', Auth::user()->id)->first();
        $users = User::where('grade_id', '<=', $me->grade_id)->get();
        return response()->json([
            'status'=>'OK',
            'users'=>$users,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param int $userid
     * @return JsonResponse
     */
    public function setusergrade(Request $request, int $id, int $userid): JsonResponse
    {
        $user= User::where('id', $userid)->first();
        $user->grade_id = $id;
        $user->save();
        event(new Notify('Le grade a été bien changé ! ',1));
        return \response()->json(['status'=>'OK']);
    }

    /**
     * @return JsonResponse
     */
    public function checkConnexion(): JsonResponse
    {
        if(Auth::user()){
            return response()->json(['session'=>true]);
        }
        return response()->json(['session'=>false]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postInfos(Request $request): JsonResponse
    {
        $living = $request->living;
        $tel = $request->tel;
        $compte= $request->compte;
        $user = User::where('id', Auth::id())->first();
        $user->liveplace= $living;
        $user->tel = $tel;
        $user->compte = $compte;
        $user->save();
        Http::post(env('WEBHOOK_INFOS'),[
            'username'=> "BCFD - Intranet",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                [
                    'title'=>'Numéro de compte',
                    'color'=>'16776960',
                    'fields'=>[
                        [
                            'name'=>'Prénom Nom : ',
                            'value'=>Auth::user()->name,
                            'inline'=>false
                        ],[
                            'name'=>'Numéro de téléphone : ',
                            'value'=>$user->tel,
                            'inline'=>false
                        ],[
                            'name'=>'Conté habité : ',
                            'value'=>$user->liveplace,
                            'inline'=>false
                        ],[
                            'name'=>'Numéro de compte : ',
                            'value'=>$user->compte,
                            'inline'=>false
                        ]
                    ],
                ]
            ]
        ]);
        return \response()->json(['status'=>'OK'],201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function GetUserPerm(Request $request): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        $grade = $user->GetGrade;
        $perm = [
            'acces'=>$grade->perm_0,
            'HS_rapport'=>$grade->perm_1,
            'HS_dossier'=>$grade->perm_2,
            'HS_BC'=>$grade->perm_3,
            'factures_PDF'=>$grade->perm_4,
            'add_factures'=>$grade->perm_5,
            'rapport_create'=>$grade->perm_6,
            'add_BC'=>$grade->perm_7,
            'remboursement'=>$grade->perm_8,
            'infos_edit'=>$grade->perm_9,
            'vol'=>$grade->perm_10,
            'rapport_horaire'=>$grade->perm_11,
            'service_modify'=>$grade->perm_12,
            'time_modify'=>$grade->perm_13,
            'perso_list'=>$grade->perm_14,
            'set_pilot'=>$grade->perm_15,
            'edit_perm'=>$grade->perm_16,
            'post_annonces'=>$grade->perm_17,
            'logs_acces'=>$grade->perm_18,
            'validate_forma'=>$grade->perm_19,
            'create_forma'=>$grade->perm_20,
            'forma_publi'=>$grade->perm_21,
            'forma_delete'=>$grade->perm_22,
            'access_stats'=>$grade->perm_23,
            'HS_facture'=>$grade->perm_24,
            'content_mgt'=>$grade->perm_25,
            'user_id'=>$user->id
        ];
        return \response()->json(['status'=>'ok', 'perm'=>$perm, 'user'=>$user]);
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

    public function getAllGrades(): JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        $grades = Grade::where('id', '<=', $user->grade_id)->get();
        return \response()->json(['status'=>'OK','grades'=>$grades]);
    }

    public function changePerm(string $perm, string $grade_id): JsonResponse
    {
        $grade = Grade::where('id', $grade_id)->first();
        $grade[$perm] = !$grade[$perm];
        $grade->save();
        event(new Notify('Vous avez changé une permissions',1));
        return \response()->json(['status'=>'OK'],201);
    }

    public function changeState(Request $request,string $user_id,string $state): JsonResponse
    {
        $user = User::where('id', (int) $user_id)->first();
        $user->serviceState = $state;
        $user->save();
        event(new Notify('Etat de service mis à jour',1));
        return response()->json(['status'=>'OK'],200);
    }

}
