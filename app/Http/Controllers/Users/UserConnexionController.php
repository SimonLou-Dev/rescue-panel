<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Events\UserRegisterEvent;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmbedBCGenerator;
use App\Jobs\ProcessEmbedPosting;
use App\Models\Grade;
use App\Models\LogDb;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UserConnexionController extends Controller
{

    use AuthenticatesUsers;

        public function __construct() {
            $this->middleware(['web']);
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
        $request->validate([
            'compte'=> 'required|digits_between:3,7|integer',
            'tel'=> 'required|digits_between:6,15|integer',
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
        ]);

        $user = User::where('id', Auth::id())->first();
        $user->liveplace= $request->living;
        $user->name = $request->name;
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
                        'value'=>$user->name,
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
     * @return JsonResponse|RedirectResponse
     */
    public function callback(Request $request): JsonResponse|RedirectResponse
    {
        $auth = Socialite::driver('discord')->user();


        $servers = Http::withToken($auth->token)->get('https://discord.com/api/v9/users/@me/guilds')->body();

        $bcfd = false;
        $glite = false;
        foreach(json_decode($servers) as $server){
            if($server->id == "792491489837711360"){
                $bcfd = true;
            }
            if($server->id == "704129979243561040"){
                $glite = true;
            }
        }

        if(!$bcfd || !$glite){
            return response()->json([
                'status' => 'ERROR',
                'raison'=> 'Not on discord Servers',
                'datas' => []
            ], 200);
        }


        $userreq = Http::withToken($auth->token)->get('https://discord.com/api/v9/users/@me');
        $userinfos = json_decode($userreq->body());

        $countMail = User::where('email', $userinfos->email)->count();
        $countId = User::where('discord_id', $userinfos->id)->count();

        if($countMail != $countId){
            return response()->json([
                'status' => 'ERROR',
                'raison'=> 'Email or account taken',
                'datas' => []
            ], 200);
        }else if($countId == 1){
            $user = User::where('discord_id', $userinfos->id)->first();
            $user->password = Hash::make($auth->token);
            $user->save();
        }else{
            $createuser = new User();
            $createuser->password = Hash::make($auth->token);
            $createuser->email =  $userinfos->email;
            $createuser->discord_id = $userinfos->id;
            $defaultGrade = Grade::where('default',true)->first();
            $createuser->grade_id = $defaultGrade->id;
            $createuser->save();
            $user = $createuser;
            $logs = new LogDb();
            $logs->user_id = $createuser->id;
            $logs->action = 'register';
            $logs->desc = $this->request->header('x-real-ip') . ' ' . $user->id;
            $logs->save();
            $embed = [
                [
                    'title'=>'Compte créé : (environement : ' . env('APP_ENV') . ')',
                    'color'=>'13436400 ',
                    'fields'=>[
                        [
                            'name'=>'Discord id : ',
                            'value'=>$createuser->discord_id,
                            'inline'=>false
                        ],[
                            'name'=>'ID : ',
                            'value'=>$createuser->id,
                            'inline'=>false
                        ],[
                            'name'=>'email : ',
                            'value'=>$createuser->email,
                            'inline'=>false
                        ],[
                            'name'=>'IP : ',
                            'value'=>$request->header('x-real-ip'),
                            'inline'=>false
                        ],[
                            'name'=>'Discord name : ',
                            'value'=> $auth->nickname,
                            'inline'=>false,
                        ]
                    ],
                    'footer'=>[
                        'text' => date('d/m/Y H:i:s'),
                    ]
                ]
            ];
            $this->dispatch(new ProcessEmbedPosting(env('WEBHOOK_BUGS'), $embed, null));
        }

        $user->getGrade();
        Auth::login($user);

        return $this::redirector($user);
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function fake(Request $request): JsonResponse|RedirectResponse
    {
        $id = $request->query('id');
        $mail = $request->query('email');
        $token = 'azkgenjazehr';

        $countMail = User::where('email', $mail)->count();
        $countId = User::where('discord_id', $id)->count();

        if($countMail != $countId){
            return response()->json([
                'status' => 'ERROR',
                'raison'=> 'Email or account taken',
                'datas' => []
            ], 200);
        }else if($countId == 1){
            $user = User::where('discord_id', $id)->first();
            $user->password = Hash::make($token);
            $user->save();
        }else{
            $createuser = new User();
            $createuser->password = Hash::make($token);
            $createuser->email =  $mail;
            $createuser->discord_id = $id;
            $defaultGrade = Grade::where('default',true)->first();
            $createuser->grade_id = $defaultGrade->id;
            $createuser->save();
            $user = $createuser;
            $logs = new LogDb();
            $logs->user_id = $createuser->id;
            $logs->action = 'register';
            $logs->desc = $this->request->header('x-real-ip') . ' ' . $user->id;
            $logs->save();
            $embed = [
                [
                    'title'=>'Compte créé : (environement : ' . env('WEBHOOK_BUGS') . ')',
                    'color'=>'13436400 ',
                    'fields'=>[
                        [
                            'name'=>'Discord id : ',
                            'value'=>$createuser->discord_id,
                            'inline'=>false
                        ],[
                            'name'=>'ID : ',
                            'value'=>$createuser->id,
                            'inline'=>false
                        ],[
                            'name'=>'email : ',
                            'value'=>$createuser->email,
                            'inline'=>false
                        ],[
                            'name'=>'IP : ',
                            'value'=>$request->header('x-real-ip'),
                            'inline'=>false
                        ],[
                            'name'=>'Discord name : ',
                            'value'=> 'TestUser#0000',
                            'inline'=>false,
                        ]
                    ],
                    'footer'=>[
                        'text' => date('d/m/Y H:i:s'),
                    ]
                ]
            ];
            $this->dispatch(new ProcessEmbedPosting(env('WEBHOOK_BUGS'), $embed, null));
        }

        $user->getGrade();
        Auth::login($user);

        return $this::redirector($user);

    }

    private static  function redirector(User $user){

        if(\Gate::allows('access', $user)){
            if(is_null($user->name || is_null($user->compte) || is_null($user->liveplace) || is_null($user->tel))){
                $redirect =  redirect()->route('informations');
            }else{
                $redirect = redirect()->route('dashboard');
            }
        }else{
            $redirect = redirect()->route('cantaccess');
        }
        return $redirect;
    }
}
