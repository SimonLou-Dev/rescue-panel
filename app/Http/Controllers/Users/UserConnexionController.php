<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Events\UserRegisterEvent;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmbedBCGenerator;
use App\Jobs\ProcessEmbedPosting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserConnexionController extends Controller
{
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
        $request->validate([
            'compte'=> 'required|digits_between:3,7|integer',
            'tel'=> 'required|digits_between:6,15|integer',
        ]);

        $user = User::where('id', Auth::id())->first();
        $user->liveplace= $request->living;
        $user->tel = $request->tel;
        $user->compte = $request->compte;
        $user->save();
        $embed = [
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
        ];
        $this->dispatch(new ProcessEmbedPosting([env('WEBHOOK_INFOS')],$embed,null));

        return \response()->json(['status'=>'OK', 'accessRight'=>$user->grade_id>1],201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request  $request): JsonResponse
    {
        $pseudo = $request->pseudo;
        $mail = $request->email;
        $psw = $request->psw;
        if(User::where('email', $mail)->count() != 0){
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
            UserRegisterEvent::dispatch($createuser);
            Auth::login($newuser);
            Session::push('user_grade', $newuser->GetGrade);
            if(Auth::check()){
                $embed = [
                    [
                        'title'=>'Compte créé : (environement : ' . env('APP_ENV') . ')',
                        'color'=>'13436400 ',
                        'fields'=>[
                            [
                                'name'=>'Nom : ',
                                'value'=>$newuser->name,
                                'inline'=>false
                            ],[
                                'name'=>'ID : ',
                                'value'=>$newuser->id,
                                'inline'=>false
                            ],[
                                'name'=>'email : ',
                                'value'=>$newuser->email,
                                'inline'=>false
                            ],[
                                'name'=>'IP : ',
                                'value'=>$request->ip(),
                                'inline'=>false
                            ]
                        ],
                        'footer'=>[
                            'text' => date('d/m/Y H:i:s'),
                        ]
                    ]
                ];
                $this->dispatch(new ProcessEmbedPosting([env('WEBHOOK_BUGS')], $embed, null));

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

                if(($user->grade_id >= 2 && $user->grade_id < 12) && is_null($user->matricule)){
                    $users = User::whereNotNull('matricule')->where('grade_id', '>',1)->where('grade_id', '<',12)->get();
                    $matricules = array();
                    foreach ($users as $usere){
                        array_push($matricules, $usere->matricule);
                    }
                    $generated = null;
                    while(is_null($generated) || array_search($generated, $matricules)){
                        $generated = random_int(10, 99);
                    }
                    $user->matricule = $generated;
                    $user->save();
                    event(new Notify('Vous avez le matricule ' . $generated,1));
                }


                if($user->liveplace != null && $user->tel != null && $user->compte != null){
                    if($user->grade_id > 1 ){
                        $returned = response()->json([
                            'status'=>'OK',
                            'user'=>$user,
                            'authed'=>Auth::check(),
                        ]);
                    }else{
                        $returned = response()->json([
                            'status'=>"ANA",
                            'user'=>$user,
                            'authed'=>Auth::check(),
                        ]);
                    }
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
}
