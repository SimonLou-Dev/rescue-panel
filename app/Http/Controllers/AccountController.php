<?php

namespace App\Http\Controllers;


use App\Events\Notify;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use function PHPUnit\Framework\directoryExists;

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
        $request->validate([
            'name'=> 'required|max:255',
            'compte'=> 'required|digits_between:3,7|integer',
            'tel'=> 'required|digits_between:8,15|integer',
            'liveplace'=> 'required',
            'email'=>'required|email'
        ]);

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
                'username'=> "BCFD - MDT",
                'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
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
        event(new Notify('Vos informations on été enregistrées', 1));
        return response()->json(['status'=>'OK'],201);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeMdp(Request $request): JsonResponse
    {
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addBgImg(Request $request): JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        $img = $request->get('image');
        $imgname = Auth::user()->id . '_' . time() . '.' . explode('.', $img)[1];
        $path = "/storage/user_background/";
        $dir = public_path($path . Auth::user()->id);
        $user->bg_img = $imgname;
        $user->save();
        if(!is_dir($dir)){
            mkdir($dir);
        }
        FileController::moveTempFile($img, $dir . '/' . $imgname);
        event(new Notify('', 1));
        return response()->json([],201);
    }

    /**
     * @return JsonResponse
     */
    public function deleteBgImg(): JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        if($user->bg_img != null){
            $dir = public_path('storage/user_background/' . Auth::user()->id.'/');
            File::delete($dir.$user->bg_img);
            $user->bg_img = null;
            $user->save();
        }
        return response()->json([],201);
    }
}
