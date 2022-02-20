<?php

namespace App\Http\Controllers;


use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Events\UserUpdated;
use App\Http\Controllers\Users\UserGradeController;
use App\Jobs\ProcessEmbedPosting;
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
            'compte'=> 'required|digits_between:2,8|integer',
            'tel'=> 'required|regex:/555-\d\d/',
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'liveplace'=> ['required'],
            'matricule'=>['unique:App\Models\User,matricule','nullable']
        ]);

        $name= $request->name;
        $compte = $request->compte;
        $tel = $request->tel;
        $liveplace=  $request->liveplace;




        $user= User::where('id', Auth::user()->id)->first();

        $changed = false;
        $nameC = $user->name != $name;
        $compteC = $compte != $user->compte;
        $telC = $tel != $user->tel;
        $liveplaceC = $liveplace != $user->liveplace;
        $matriculeC = $request->matricule != $user->matricule;


        if($nameC || $compteC || $telC || $liveplaceC || $matriculeC){
            $changed = true;
        }
        if($changed){
            $embed = [
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
                        ],[
                            'name'=>'Matricule : ',
                            'value'=> ($matriculeC ? '~~'.$user->matricule.'~~ ' : '') . $request->matricule,
                            'inline'=>false
                        ]
                    ],
                ]
            ];
            if($user->fire){
                \Discord::postMessage(DiscordChannel::FireInfos, $embed);
            }else{
                \Discord::postMessage(DiscordChannel::MedicInfos, $embed);
            }
        }
        $user->name = $name;
        $user->compte = $compte;
        $user->tel = $tel;
        if(isset($request->matricule)){
                $user->matricule = $request->matricule;
        }
        $user->liveplace = $liveplace;
        $user->save();
        UserUpdated::broadcast($user);
        event(new Notify('Vos informations on été enregistrées', 1));
        $grade = new UserGradeController();
       // return $grade->GetUserPerm($request);

        return response()->json([],201);

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
        $path = "/public/user_background/". Auth::user()->id;
        $dir = Storage::path($path );
        $user->bg_img = $imgname;
        $user->save();
        if(!is_dir($dir)){
            mkdir($dir);
        }
        Storage::move('/public/temp/'.$img, $path . '/' . $imgname);
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
            $dir = Storage::path('public/user_background/' . Auth::user()->id.'/');
            File::delete($dir.$user->bg_img);
            $user->bg_img = null;
            $user->save();
        }
        return response()->json([],201);
    }
}
