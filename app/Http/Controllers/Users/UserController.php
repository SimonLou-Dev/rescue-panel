<?php

namespace App\Http\Controllers\Users;

use _HumbugBox15516bb2b566\Nette\Utils\DateTime;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Intervention;
use App\Models\User;
use App\Exporter\ExelPrepareExporter;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
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

    /**
     * @param Request $request
     * @param string $user_id
     * @param string $state
     * @return JsonResponse
     */
    public function changeState(Request $request, string $user_id, string $state): JsonResponse
    {
        $user = User::where('id', (int)$user_id)->first();
        $user->serviceState = ($state == 'null' ? null : $state);
        $user->save();
        event(new Notify('Etat de service mis à jour', 1));
        return response()->json(['status' => 'OK'], 200);
    }

    /**
     * @param Request $request
     * @param string $discordid
     * @param string $id
     * @return JsonResponse
     */
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

    /**
     * @param string $user_id
     * @return JsonResponse
     */
    public function getUserNote(string $user_id): JsonResponse
    {
        $user = User::where('id', $user_id)->first();
        return \response()->json([
            'status'=> 'ok',
            'note'=> (array) array_reverse(json_decode($user->note))
        ]);
    }

    /**
     * @param string $user_id
     * @return JsonResponse
     */
    public function getUserSanctions(string $user_id): JsonResponse
    {
        $user = User::where('id', $user_id)->first();
        return \response()->json([
            'status'=> 'ok',
            'sanctions'=> (array) array_reverse(json_decode($user->sanctions))
        ]);
    }

    /**
     * @param string|null $user_id
     * @return JsonResponse
     */
    public function getUserInfos(string $user_id = NULL): JsonResponse
    {
        if(is_null($user_id)){
            $user_id = Auth::id();
        }
        $user = User::where('id', $user_id)->first();
        $user->GetGrade;

        return \response()->json([
            'status'=> 'ok',
            'infos'=> $user
        ]);
    }

    /**
     * @param string $user_id
     * @return JsonResponse
     */
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
        $user = User::where('id', $id)->first();

        $request->validate([
            'note'=>'required'
        ]);
        $newnote = [
            'id' => Str::uuid(),
            'sender' => Auth::user()->name,
            'note' => $request->note,
            'posted_at'=>date('d/m/Y à H:i')
        ];


        if(is_null($user->note)){
            $notes = [$newnote];
        }else{
            $notes = json_decode($user->note);
            array_push($notes, $newnote);
        }
        $user->note = json_encode($notes);
        $user->save();

        return \response()->json([],201);
    }

    public function removeUserNote(Request $request, string $id, string $note_id)
    {
        $user = User::where('id',  $id)->first();
        $notes = json_decode($user->note);
        $find = null;
        $a = 0;

        while($a < count($notes)){
            if($notes[$a]->id = $note_id){
                $find =$a;
            }
            $a++;
        }

        if(!is_null($find)){
            unset($notes[$find]);
            $user->note = $notes;
            $user->save();
            event(new Notify('Cette note à été supprimée',1));
        }else{
            event(new Notify('Une erreur est survenue',4));
        }
        return \response()->json([]);

    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function addUserSanction(Request $request, string $id): JsonResponse
    {
        $user = User::where('id',  $id)->first();
        $baseuser = $user;
        $sanctionsinfos[] = array();
        if(!is_null($user->sanctions)){
            $sanctionsinfos = (array) json_decode($user->sanctions);
        }
        $array = array();
        $prononcer = User::where('id', Auth::user()->id)->first();
        $reqinfos= $request->get('infos');
        $array['prononcedam'] = date('d/m/Y \à H\hi');
        $array['prononcedby'] = $prononcer->name;

        switch ($request->sanctions){
            case "1": //Avertissement
                $array['type'] = "Avertissement";
                $text = "__**Type :**__ Avertissement";
                break;
            case "2": //MAP
                $array['type'] = "Mise à pied";
                $array['ended_at'] = date('d/m/Y', strtotime($reqinfos['map_date'])) . ' à ' . date('H:i', strtotime($reqinfos['map_time']));
                $format = 'Y-m-d H:i';
                $end= DateTime::createFromformat($format, $reqinfos['map_date'] . ' ' . $reqinfos['map_time']);
                $now = new DateTime('now');
                $diff = $end->diff($now);
                $array['diff'] = $diff->format('%d jours et %H heures');
                $text = "__**Type :**__ Mise à pied \n __**Durée :**__ " . $array['diff'] . " \n __**Fin le :**__ " . $array['ended_at'];
                break;
            case "3": //degradation
                $array['type'] = "Dégradation";
                $array['ungrad'] = $baseuser->GetGrade->name . ' -> ' . Grade::where('id', $baseuser->GetGrade->id - 1)->first()->name;
                $text = "__**Type :**__ Perte d'un grade \n __**Ancien grade :**__  ". $baseuser->GetGrade->name . "\n __**Nouveau grade :**__" . Grade::where('id', $baseuser->GetGrade->id - 1)->first()->name;
                $user->grade_id = $user->grade_id -1;
                break;
            case "4": //dehors
                $array['type'] = "Exclusion";
                $array['noteLic'] = $reqinfos['note_lic'];
                $text = "__**Type:**__ Exclusion \n __**Infos licenciement:**__ " . $array['noteLic'];
                UserGradeController::removegradeFromuser((int) $id);
                break;
        }
        $array['raison'] = $reqinfos['raison'];
        $final = ">>> ***__Nouvelle sanction :__*** \n __**De :**__". $array['prononcedby'] . "\n __**A :**__ " . ($user->discord_id != null ? ("<@" . $user->discord_id . "> ") : "") . $user->name . " \n ". $text . "\n **__Prononcé le :__** " . $array['prononcedam'] . " \n **__Raison :__** " . $array['raison'];

        array_push($sanctionsinfos, $array);

        $user->sanctions = json_encode($sanctionsinfos);

        $user->save();

        Http::post(env('WEBHOOK_SANCTIONS'),[
            'username'=> "BCFD - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'content'=>$final

        ]);


        return \response()->json(['status'=>'ok'],201);

    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function ModifyUserMaterial(Request $request, string $id): JsonResponse
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

    /**
     * @param Request $request
     * @param string $id
     */
    public function userQuitService(Request $request, string $id)
    {
        $user = User::where('id', $id)->first();
        UserGradeController::removegradeFromuser($user->id);


        event(new Notify('La démission a été prise en compte',1));
        $prononcer = User::where('id', Auth::user()->id)->first();

        Http::post(env('WEBHOOK_SANCTIONS'),[
            'username'=> "BCFD - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'content'=>">>> ***__Démission :__*** \n **__Personnel :__** " . ($user->discord_id != null ? ("<@" . $user->discord_id . "> ") : "") . $user->name . "\n **__Déclaré par :__** ".$prononcer->name
        ]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportListPersonnelExel(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $users = User::where('grade_id', '>', 1)->where('grade_id', '<', 10)->orderByDesc('grade_id')->get();

        $column[] = array('id','nom', 'matricule', 'grade', 'discordid', 'tel', 'compte', 'pilote', 'nombre de sanctions');

        foreach ($users as $user){

            $sanctions = json_decode($user->sanctions);
            $column[] = [
                'id' => $user->id,
                'nom' => $user->name,
                'matricule' => $user->matricule ? $user->matricule : '',
                'grade' => $user->GetGrade->name,
                'discordid' => $user->discord_id ? $user->discord_id : '',
                'tel' => $user->tel,
                'compte' => $user->compte,
                'pilote' => $user->pilote ? 'oui' : 'non',
                'nombre de sanctions' => count($sanctions),
            ];

        }
        $export = new ExelPrepareExporter($column);

        return Excel::download((object)$export, 'BCFD_UserExport_'. now()->timestamp .'.xlsx');
    }

}
