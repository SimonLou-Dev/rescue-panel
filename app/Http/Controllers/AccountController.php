<?php

namespace App\Http\Controllers;


use App\Events\Notify;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    /**
     * @return JsonResponse
     */
    public function getInfos(): JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        return response()->json(['status'=>'OK', 'user'=>$user]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateInfos(Request $request): JsonResponse
    {
        $name= (string) $request->name;
        $compte = (int) $request->compte;
        $tel = (int) $request->tel;
        $liveplace= (string) $request->liveplace;
        $email =(string) $request->email;

        $user= User::where('id', Auth::user()->id)->first();

        $changed = false;
        $nameC = $user->name != $name;
        $compteC = $compte != $user->compte;
        $telC = $tel != $user->tel;
        $liveplaceC = $liveplace != $user->liveplace;


        if($nameC || $compteC || $telC || $liveplaceC){
            $changed = true;
        }
        if($changed){
            Http::post(env('WEBHOOK_INFOS'),[
                'embeds'=>[
                    [
                        'title'=>"Numéro de compte *(Changement d'informations)*",
                        'color'=>'16776960',
                        'fields'=>[
                            [
                                'name'=>'Prénom Nom : ',
                                'value'=>($nameC ? '~~'.$user->name.'~~ ' : '') . $name,
                                'inline'=>false
                            ],[
                                'name'=>'Numéro de téléphone : ',
                                'value'=> ($telC ? '~~'.$user->tel.'~~ ' : '') . $tel,
                                'inline'=>false
                            ],[
                                'name'=>'Conté habité : ',
                                'value'=>($liveplaceC ? '~~'.$user->liveplace.'~~ ' : '') . $liveplace,
                                'inline'=>false
                            ],[
                                'name'=>'Numéro de compte : ',
                                'value'=> ($compteC ? '~~'.$user->compte.'~~ ' : '') . $compte,
                                'inline'=>false
                            ]
                        ],
                    ]
                ]
            ]);
        }
        $user->name = $name;
        $user->compte = $compte;
        $user->tel = $tel;
        $user->email = $email;
        $user->liveplace = $liveplace;
        $user->save();
        return response()->json([$changed, $nameC, $compteC, $telC, $liveplaceC]);
        event(new Notify('Vos informations on été enregistrées', 1));
        return response()->json(['status'=>'OK'],201);

    }

    public function changeMdp(Request $request){
        $user = User::where('id', Auth::user()->id)->first();
        if(!Hash::check($request->last, $user->password)){
            event(new Notify('Votre ancien mot de passe ne correspond pas',4));
            return response()->json(['status'=>'PAS OK'], 500);
        }
        if($request->newmdp !== $request->mdprepet){
            event(new Notify('Les deux mots de passes entrés ne correspondent pas',4));
            return response()->json(['status'=>'PAS OK'], 500);
        }

        $user->password = Hash::make($request->newmdp);
        $user->save();
        event(new Notify('Mot de passe changé',1));
        return response()->json(['status'=>'OK'], 201);
    }
}
