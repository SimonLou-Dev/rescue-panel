<?php

namespace App\Http\Controllers\Users;

use _HumbugBox15516bb2b566\Nette\Utils\DateTime;
use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Jobs\ProcessEmbedPosting;
use App\Models\Grade;
use App\Models\Intervention;
use App\Models\LogServiceState;
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
        $this->authorize('viewPersonnelList', User::class);
        $me = User::where('id', Auth::user()->id)->first();
        $meService = Session::get('service')[0];
        if($meService === null || $meService === '') $meService = $me->service;
        $users = User::search($request->query('query'))->get();
        $queryPage = (int) $request->query('page');
        $readedPage = ($queryPage ?? 1) ;
        $readedPage = (max($readedPage, 1));
        $forgetable = array();
        for($a = 0; $a < $users->count(); $a++){
            if(!$me->dev){
                $user = $users[$a];
                if($meService === "SAMS"){
                    if(!$user->isInMedicUnit()){
                        array_push($forgetable, $a);
                    }
                }else if($meService === "LSCoFD"){
                    if(!$user->isInFireUnit()){
                        array_push($forgetable, $a);
                    }
                }
            }
        }

        foreach ($forgetable as $it){
            $users->forget($it);
        }


        $users = $users->filter(function ($item){
            return \Gate::allows('view', $item);
        });


        $finalList = $users->skip(($readedPage-1)*20)->take(20);

        foreach ($finalList as $user){
            if($meService === 'SAMS'){
                $user->grade = $user->GetMedicGrade;
            }if($meService === 'LSCoFD'){
                $user->grade =$this->GetFireGrade;
            }

        }

        $url = $request->url() . '?query='.urlencode($request->query('query')).'&page=';
        $totalItem = $users->count();
        $valueRounded = ceil($totalItem / 5);
        $maxPage = (int) ($valueRounded == 0 ? 1 : $valueRounded);
        //Creation of Paginate Searchable result
        $array = [
            'current_page'=>$readedPage,
            'last_page'=>$maxPage,
            'data'=> $finalList,
            'next_page_url' => ($readedPage === $maxPage ? null : $url.($readedPage+1)),
            'prev_page_url' => ($readedPage === 1 ? null : $url.($readedPage-1)),
            'total' => $totalItem,
        ];

        $grades = Grade::where('service', $meService)->get();
        $grades = $grades->filter(function ($item){
            return \Gate::allows('view', $item);
        });


        return response()->json([
            'status' => 'OK',
            'users' => $array,
            'serviceGrade'=>$grades,
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
        $this->authorize('setPilote', $user);
        $user->pilote = !$user->pilote;
        $user->save();
        UserUpdated::broadcast($user);
        return \response()->json(['status' => 'OK'], 201);
    }

    public function setService(Request $request, string $service){
        $user = User::where('id',Auth::user()->id)->first();
        Session::push('service', $service);
        $user->service = $service;
        $user->save();
        UserUpdated::dispatch($user);
        return \response()->json([],202);
    }


    /**
     * @param Request $request
     * @param string $discordid
     * @param string $id
     * @return JsonResponse
     */
    public function setCrossService(Request $request, string $id): JsonResponse
    {

        $user = User::where('id', $id)->first();
        if($user->crossService){
            if($user->fire)$user->medic_grade_id = 1;
            if($user->medic)$user->fire_grade_id = 1;
            Notify::broadcast('Grade retiré',1, Auth::user()->id);
        }

        $user->crossService=!$user->crossService;
        $user->save();

        Notify::broadcast('Modification enregistrée',1, Auth::user()->id);
        return response()->json(['status' => 'OK'], 200);
    }



    public function getSheet(string $user_id){
        $user = User::where('id',$user_id)->first();
        $user->GetMedicGrade;
        $user->GetFireGrade;
        $user->note = ($user->note !== null ? json_decode($user->note) : null);
        $user->materiel = ($user->materiel !== null ? json_decode($user->materiel) : null);
        $user->sanctions = ($user->sanctions !== null ? json_decode($user->sanctions) : null);
        return \response()->json([
            'user'=>$user,
        ]);

    }


    public function addUserNote(Request $request, string $id)
    {
        $user = User::where('id', $id)->first();
        $service = Session::get('service')[0];

        $request->validate([
            'note'=>'required'
        ]);
        $newnote = [
            'id' => Str::uuid(),
            'sender' => Auth::user()->name ." (${service})",
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

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function addUserSanction(Request $request, string $id): JsonResponse
    {
        $baseuser = User::where('id',  $id)->first();
        $prononcer = User::where('id', Auth::user()->id)->first();

        $service = Session::get('service')[0];

        $sanctionsinfos[] = array();

        if(!is_null($baseuser->sanctions)){
            $sanctionsinfos = (array) json_decode($baseuser->sanctions);
        }

        $reqinfos= $request->get('infos');
        $array['prononcedam'] = date('d/m/Y');
        $array['prononcedby'] = $prononcer->name ." (${service})";

        switch ($request->sanction){
            case "1": //Avertissement
                $array['type'] = "Avertissement";
                $text = "__**Type :**__ Avertissement";
                break;
            case "2": //MAP
                $array['type'] = "Mise à pied";
                $array['duration'] = $reqinfos['map_date'];
                $text = "__**Type :**__ Mise à pied \n __**Durée :**__ " . $array['duration'];
                break;
            case "3": //dehors
                $array['type'] = "Exclusion";
                $array['noteLic'] = $reqinfos['note_lic'];
                $text = "__**Type:**__ Exclusion \n __**Infos licenciement:**__ " . $array['noteLic'];
                UserGradeController::removegradeFromuser((int) $id);
                break;
            default: break;
        }
        $array['reason'] = $reqinfos['reason'];
        $final = ">>> ***__Nouvelle sanction :__*** \n __**De :**__". $array['prononcedby'] . "\n __**A :**__ " . ($baseuser->discord_id != null ? ("<@" . $baseuser->discord_id . "> ") : "") . $baseuser->name . " \n ". $text . "\n **__Prononcé le :__** " . $array['prononcedam'] . " \n **__Raison :__** " . $array['reason'];

        array_push($sanctionsinfos, $array);

        $baseuser->sanctions = json_encode($sanctionsinfos);

        $baseuser->save();
        UserUpdated::broadcast($baseuser);

        if($baseuser->isInFireUnit()){
            \Discord::postMessage(DiscordChannel::FireSanctions, [], null, $final);
        }

        if($baseuser->isInMedicUnit()){
            \Discord::postMessage(DiscordChannel::MedicSanctions, [], null, $final);
        }

        $logs = new LogsController();
        $logs->SanctionsLogging($array['type'], $baseuser->id, $prononcer->id);

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
        $base = (array) is_null($user->materiel) ? null : json_decode($user->materiel);
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
        $embed = [
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
        ];

        if($user->isInFireUnit()){
            \Discord::postMessage(DiscordChannel::FireLogistique, $embed);
        }

        if($user->isInMedicUnit()){
            \Discord::postMessage(DiscordChannel::MedicLogistique, $embed);
        }

        UserUpdated::broadcast($user);
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
        UserUpdated::broadcast($user);
        $msg =   ">>> ***__Démission :__*** \n **__Personnel :__** " . ($user->discord_id != null ? ("<@" . $user->discord_id . "> ") : "") . $user->name . "\n **__Déclaré par :__** ".$prononcer->name;

        if($user->isInFireUnit()){
            \Discord::postMessage(DiscordChannel::FireSanctions, [], null, $msg);
        }

        if($user->isInMedicUnit()){
            \Discord::postMessage(DiscordChannel::MedicSanctions, [], null, $msg);
        }


    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportListPersonnelExel(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->authorize('viewPersonnelList', User::class);
        $me = User::where('id', Auth::user()->id)->first();
        $meService = Session::get('service')[0];
        $users = User::all();
        $forgetable = array();
        for($a = 0; $a < $users->count(); $a++){




            if(!$me->dev){
                $user = $users[$a];
                if($meService === "SAMS"){
                    if(!($user->isInMedicUnit())){
                        array_push($forgetable, $a);
                    }
                }else if($meService === "LSCoFD"){
                    if(!($user->isInFireUnit())){
                        array_push($forgetable, $a);
                    }
                }
            }
        }

        foreach ($forgetable as $it){
            $users->forget($it);
        }
        foreach ($users as $user){
            if($meService === 'SAMS'){
                $user->grade = $user->GetMedicGrade;
            }if($meService === 'LSCoFD'){
                $user->grade =$this->GetFireGrade;
            }

        }


        $users = $users->filter(function ($item){
            $medic = false;
            $fire = false;
            if($item->isInFireUnit() && $item->GetFireGrade->name !== 'default') $fire = true;
            if($item->isInMedicUnit() && $item->GetMedicGrade->name !== 'default') $medic = true;

            return \Gate::allows('view', $item) && ( $fire || $medic);
        });

        $column[] = array('id','nom', 'matricule', 'grade', 'discordid', 'tel', 'compte', 'pilote','service d\'arrivée','cross service', 'nombre de sanctions');

        foreach ($users as $user){

            $sanctions = $user->sanctions != null ? json_decode($user->sanctions) : [];
            $column[] = [
                'id' => $user->id,
                'nom' => $user->name,
                'matricule' => $user->matricule ? $user->matricule : '',
                'grade' => $user->grade->name,
                'discordid' => $user->discord_id ? $user->discord_id : '',
                'tel' => $user->tel,
                'compte' => $user->compte,
                'pilote' => $user->pilote ? 'oui' : 'non',
                "service d'arrivée"=>$user->medic ? 'SAMS' : 'LSCoFD',
                'cross service'=>$user->crossService ? 'oui' : 'non',
                'nombre de sanctions' => count($sanctions),
            ];

        }
        $export = new ExelPrepareExporter($column);

        return Excel::download((object)$export, $meService.'_UserExport_'. now()->timestamp .'.xlsx');
    }

}
