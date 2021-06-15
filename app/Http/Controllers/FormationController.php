<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\Certification;
use App\Models\Formation;
use App\Models\FormationsQuestion;
use App\Models\FormationsResponse;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class FormationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    /**
     * @return JsonResponse
     */
    public function getUsersCertifications(): JsonResponse
    {
        $forma = Formation::all();
        $grades = Grade::where('perm_0', true)->get();
        $list = array();
        foreach ($grades as $grade){
            array_push($list, $grade->id);
        }
        $users = User::whereIn('grade_id', $list)->orderBy('id', 'Asc')->get();
        foreach ($users as $user){
            $user->GetCertifications;
        }
        return response()->json([
            'status'=>'OK',
            'users'=>$users,
            'certifs'=>$forma,
            'nbrForma'=>count($forma),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getFormations(string $page =null, string $max = null): JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        $pages = null;

        if(!is_null($max)){
            $page = (int) $page;
            $page--;
            if($user->GetGrade->perm_21 || $user->GetGrade->perm_20){
                $formations = Formation::orderByDesc('id')->skip($page*4)->take(4)->get();
                $formationsCount = Formation::count();
            }else{
                $formations = Formation::where('public', true)->skip($page*4)->take(4)->orderByDesc('id')->get();
                $formationsCount =  Formation::where('public', true)->count();
            }
            $pages = ceil($formationsCount / 4);
            $myformations = [];
            foreach ($user->GetCertifications as $certification){
                array_push($myformations, $certification->formation_id);
            }
            foreach ($formations as $formation){
                if (in_array($formation->id, $myformations)){
                    $formation->validate= true;
                }else{
                    $formation->validate = false;
                }
            }

        }else{
            if($user->GetGrade->perm_21 || $user->GetGrade->perm_20){
                $formations = Formation::orderByDesc('id')->get();
            }else{
                $formations = Formation::where('public', true)->orderByDesc('id')->get();
            }
        }


        return \response()->json(['status'=>'OK', 'formations'=>$formations, 'pages'=>$pages]);
    }

    /**
     * @param string $forma_id
     * @param string $user_id
     * @return JsonResponse
     */
    public function changeUserCertification(string $forma_id, string $user_id): JsonResponse
    {
        $forma_id = (int) $forma_id;
        $user_id = (int) $user_id;
        $certif = Certification::where('formation_id', $forma_id);
        if($certif->count() == 0){
            $certif = new Certification();
            $certif->user_id = $user_id;
            $certif->formation_id = $forma_id;
            $certif->save();
        }else{
            $certif->first()->delete();
        }
        event(new Notify('La certification a été ' . $certif->count()  == 0? 'ajoutée':'supprimée',1));
        return \response()->json(['status'=>'OK'],200);
    }

    /**
     * @param string $formation_id
     * @return JsonResponse
     */
    public function getFormationByIdAdmin(string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $formation = Formation::where('id', $formation_id)->first();
        return response()->json([
            'status'=>'OK',
            'formation'=>$formation,
            'responses'=>$formation->GetQuestions]);
    }

    /**
     * @param string $formation_id
     * @return JsonResponse
     */
    public function changeFormationVisibility(string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $formation = Formation::where('id', $formation_id)->first();
        $formation->public = !$formation->public;
        $formation->save();
        event(new Notify('La formation est maintenant ' . ($formation->public ? 'publique': 'privée'),1));
        return \response()->json(['status'=>'OK'],201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postFormation(Request $request): JsonResponse
    {
        $correction = (bool) $request->correction;
        $desc = (string) $request->desc;
        $certif= (bool) $request->certif;
        $finalnote= (bool) $request->finalnote;
        $max_try = (int) $request->max_try;
        $name = (string) $request->name;
        $time = (bool) $request->time;
        $total = (bool) $request->total;
        $question = (bool) $request->question;
        $time_str= (string) $request->time_str;
        $unic_try = (bool) $request->unic_try;
        $save = (bool) $request->save;
        $time_btw = (bool) $request->time_btw;
        $time_btw_str = (string) $request->time_btw_str;

        if($unic_try){
            $time_btw = false;
            $time_btw_str= null;
            $max_try = 1;
        }
        if(!$time_btw){
            $time_btw_str = (int)null;
        }else{
            $time_btw_str = explode(' ', $time_btw_str);
            $time_btw_str = (int) (  $time_btw_str[0] *86400) + ((int) $time_btw_str[1]*3600);
        }
        if(!$time){
            $total = false;
            $question= false;
            $time_str = "";
            $timer = (int) null;
        }else{
            $time_str = explode(':', $time_str);
            $timer = (int) ($time_str[0]*60) + ($time_str[1]);
        }


        $formation = new Formation();
        //infos bloballes
        $formation->name = $name;
        $formation->public = false;
        $formation->creator_id = Auth::user()->id;
        $formation->desc = $desc;
        //essai
        $formation->unic_try = $unic_try;
        $formation->can_retry_later = $time_btw;
        $formation->max_try = $max_try;
        $formation->time_btw_try = $time_btw_str;
        //time
        $formation->timed = $total;
        $formation->question_timed = $question;
        $formation->timer = $timer;
        //other
        $formation->correction = $correction;
        $formation->max_note = 0;
        $formation->save_on_deco = $save;
        $formation->displaynote= $finalnote;
        $formation->certify = $certif;
        $formation->save();
        event(new Notify('Vous avez ajouté une formation', 1));

        return response()->json([
            'formation'=>$formation,
        ],201);
    }

    /**
     * @param Request $request
     * @param string $formation_id
     * @return JsonResponse
     */
    public function updateFormation(Request $request, string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $correction = (bool) $request->correction;
        $desc = (string) $request->desc;
        $certif= (bool) $request->certif;
        $finalnote= (bool) $request->finalnote;
        $max_try = (int) $request->max_try;

        $name = (string) $request->name;
        $time = (bool) $request->time;
        $total = (bool) $request->total;
        $question = (bool) $request->question;
        $time_str= (string) $request->time_str;
        $unic_try = (bool) $request->unic_try;
        $save = (bool) $request->save;
        $time_btw = (bool) $request->time_btw;
        $time_btw_str = (string) $request->time_btw_str;

        if($unic_try){
            $time_btw = false;
            $time_btw_str= null;
            $max_try = 1;
        }

        if(!$time_btw){
            $time_btw_str = (int)null;
        }else{
            $time_btw_str = explode(' ', $time_btw_str);
            $time_btw_str = (int) (  $time_btw_str[0] *86400) + ((int) $time_btw_str[1]*3600);
        }
        if(!$time){
            $total = false;
            $question= false;
            $time_str = "";
            $timer = (int) null;
        }else{
            $time_str = explode(':', $time_str);
            $timer = (int) ($time_str[0]*60) + ($time_str[1]);
        }


        $formation = Formation::where('id', $formation_id)->first();
        //infos bloballes
        $formation->name = $name;
        $formation->public = false;
        $formation->creator_id = Auth::user()->id;
        $formation->desc = $desc;
        //essai
        $formation->unic_try = $unic_try;
        $formation->can_retry_later = $time_btw;
        $formation->max_try = $max_try;
        $formation->time_btw_try = $time_btw_str;
        //time
        $formation->timed = $total;
        $formation->question_timed = $question;
        $formation->timer = $timer;
        //other
        $formation->correction = $correction;
        $formation->max_note = 0;
        $formation->save_on_deco = $save;
        $formation->displaynote= $finalnote;
        $formation->certify = $certif;
        $formation->save();

        event(new Notify('Vous avez sauvegardé la formations', 1));
        return response()->json([
            'formation'=>$formation,
        ],201);
    }

    /**
     * @param Request $request
     * @param string $formation_id
     */
    public function addQuestion(Request $request, string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $formation = Formation::where('id', $formation_id)->first();
        if(!isset($formation)){
            return \response()->json([],500);
        }
        $question = new FormationsQuestion();
        $question->formation_id = $formation->id;
        $responses=$request->responses;
        $a=0;
        $Rresponse = 0;
        foreach ($responses as $response){
            $a++;
            if($response['active'] === true){
                $Rresponse++;
            }
        }

        $question->responses = json_encode($responses);
        $question->name = $request->name;
        $question->type = ($Rresponse > 1 ? 'choix multiple' : 'choix unique');
        $question->max_note = $a;
        $formation->max_note= $formation->max_note+$a;
        $question->desc = $request->description;
        $question->correction = $request->correction;
        $question->save();
        $formation->save();
        event(new Notify('Vous avez ajouté une question', 1));
        return \response()->json(['status'=>'OK', 'questionid'=>$question->id],201);
    }

    /**
     * @param string $question_id
     */
    public function deleteQuestion(string $question_id){
        $question_id = (int) $question_id;
        // a faire
    }

    /**
     * @param Request $request
     * @param string $question_id
     * @return JsonResponse
     */
    public function updateQuestion(Request $request, string $question_id): JsonResponse
    {
        $question_id = (int) $question_id;
        $question = FormationsQuestion::where('id', $question_id)->first();
        $formation = Formation::where('id', $question->GetFormation->id)->first();
        if(!isset($question)){
            return \response()->json([],500);
        }
        $responses=$request->responses;
        $a=0;
        $Rresponse = 0;
        foreach ($responses as $response){
            $a++;
            if($response['active']){
                $Rresponse++;
            }
        }
        $question->responses = json_encode($responses);
        $question->name = $request->name;
        $question->type = ($Rresponse > 1 ? 'choix multiple' : 'choix unique');
        $question->desc = $request->description;
        $question->correction = $request->correction;
        $formation->max_note = $formation->max_note - $question->max_note;
        $question->max_note = $a;
        $formation->max_note = $formation->max_note + $a;
        $formation->save();
        $question->save();
        event(new Notify('Vous avez mis a jour une question', 1));
        return \response()->json(['status'=>'OK'],201);
    }

    /**
     * @param string $formation_id
     * @return JsonResponse
     */
    public function deleteFormationById(string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $formation = Formation::where('id', $formation_id)->first();
        $formation->delete();
        event(new Notify('Formation supprimée',1));
        return \response()->json(['status'=>"OK"]);
    }

    /**
     * @param string $formation_id
     * @return JsonResponse
     */
    public function getFormationById(string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $user = User::where('id',Auth::user()->id)->first();

        $tentative = FormationsResponse::where('user_id', Auth::user()->id)->where('formation_id', $formation_id)->where('finished', false)->count() > 0;

        $formation = Formation::where('id', $formation_id)->first();
        if(((!$user->GetGrade->perm_21 && !$user->GetGrade->perm_20) && !$tentative )) {
            if ($formation->unic_try) {
                foreach ($formation->GetResponses as $response) {
                    if ($response->user_id == Auth::user()->id) {
                        event(new Notify('Cette formation est a essai unique', 2));
                        return response()->json(['status' => 'UNIC TRY'], 500);
                    }
                }
            }
            $userstry = 0;
            foreach ($formation->GetResponses as $response) {
                if ($response->user_id == Auth::user()->id) {
                    $userstry++;
                }
            }
            if ($userstry == $formation->max_try && $formation->max_try != 0) {
                event(new Notify('Vous avez épuisé toute vos tentatives', 2));
                return response()->json(['status' => 'TO MANY TRY'], 500);
            }

            if ($formation->can_retry_later) {
                $last = FormationsResponse::where('user_id', Auth::user()->id)->where('formation_id', $formation_id)->orderByDesc('id')->first();
                if (isset($last)) {
                    $time = strtotime($last->created_at);
                    $possibily = time() > $formation->time_btw_try + $time;
                    if (!$possibily) {
                        event(new Notify('Vous ne pouvez pas refaire cette formation maintenant', 2));
                        return response()->json(['status' => 'MUST WAIT', 'time' => $formation->time_btw_try + $time], 500);
                    }
                }
            }
        }

        foreach ($formation->GetQuestions as $question){
            $question->responses = json_decode($question->responses);
        }

        return response()->json([
            'status'=>'OK',
            'formation'=>$formation,
        ]);
    }

    /**
     * @param string $question_id
     * @return JsonResponse
     */
    public function getQuestionById(string $question_id): JsonResponse
    {
        $question_id = (int) $question_id;
        $question = FormationsQuestion::where('id', $question_id)->first();
        $question->responses = json_decode($question->responses);
        return \response()->json(['status'=>'OK', 'question'=>$question]);
    }

    /**
     * @param Request $request
     * @param string $question_id
     * @return JsonResponse
     */
    public function saveResponseState(Request $request, string $question_id): JsonResponse
    {
        $question_id = (int) $question_id;
        $question = FormationsQuestion::where('id',$question_id)->first();
        $response = FormationsResponse::where('finished', false)->where('user_id', Auth::user()->id)->first();
        if(!isset($response->note)){
            $response = new FormationsResponse();
            $response->finished = false;
            $response->lastquestion_id = (int) $question_id;
            $response->formation_id = $question->GetFormation->id;
            $response->user_id = Auth::user()->id;
            $response->note = (int) $request->points;
        }else{
                $response->note = $response->note + (int) $request->points;
                $response->lastquestion_id = (int) $question_id;
        }
        $response->save();
        return \response()->json(['status'=>'OK']);
    }

    /**
     * @param $formation_id
     * @return JsonResponse
     */
    public function userDisconnect($formation_id): JsonResponse
    {
        $formation_id = (int)$formation_id;
        $formation = Formation::where('id', $formation_id)->first();
        $response = FormationsResponse::where('user_id', Auth::user()->id)->where('formation_id', $formation_id)->first();
        if(!$formation->save_on_deco){
            $response->delete();
        }
        return response()->json([]);
    }

    /**
     * @param string $formation_id
     * @return JsonResponse
     */
    public function getFinalDatas(string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $formation = Formation::where('id', $formation_id)->first();
        $response = FormationsResponse::where('user_id', Auth::user()->id)->where('formation_id', $formation_id)->first();
        $responses = FormationsResponse::where('formation_id', $formation_id)->where('finished', true)->get();
        $total =0;
        foreach ($responses as $respons){
            $total = $total + $respons->note;
        }
        $formation->average_note = round($total / (count($responses)+1));
        if($formation->certify && $response->note > ($formation->max_note/3)*2){
            $certif = Certification::where('user_id',Auth::user()->id)->where('formation_id', $formation_id)->first();
            if(!isset($certif)){
                $certif = new Certification();
                $certif->formation_id = $formation_id;
                $certif->user_id= Auth::user()->id;
                $certif->save();
                $formation->success = $formation->success +1;
            }
        }
        $formation->try = $formation->try+1;
        $response->finished = true;
        $response->save();
        $formation->save();
        return \response()->json(['note'=>$response->note.'/'.$formation->max_note]);
    }

    /**
     * @param Request $request
     * @param string $question_id
     * @return JsonResponse
     */
    public function postQuestionImage(Request $request, string $question_id): JsonResponse
    {
        $question = FormationsQuestion::where('id', $question_id)->first();
        $img = $request->get('image');
        $imgname = Auth::user()->id . '_' . time() . '.' . explode('.', $img)[1];
        $path = "/storage/formations/question_img/".$question->GetFormation->id.'/'. $question_id;
        $dir = public_path($path);
        $question->img = $imgname;
        $question->save();
        if(!is_dir(public_path("/storage/formations/question_img/".$question->GetFormation->id))){
            mkdir(public_path("/storage/formations/question_img/".$question->GetFormation->id));
        }
        if(!is_dir($dir)){
            mkdir($dir);
        }
        FileController::moveTempFile($img, $dir . '/' . $imgname);
        event(new Notify('Photo ajoutée à la formations', 1));
        return response()->json([],201);

    }

    /**
     * @param Request $request
     * @param string $formation_id
     * @return JsonResponse
     */
    public function postFormationsImage(Request $request, string $formation_id): JsonResponse
    {
        $formation = Formation::where('id', $formation_id)->first();
        $img = $request->get('image');
        $imgname = Auth::user()->id . '_' . time() . '.' . explode('.', $img)[1];
        $path = "/storage/formations/front_img/".$formation_id;
        $dir = public_path($path);
        $formation->image = $imgname;
        $formation->save();
        if(!is_dir($dir)){
            mkdir($dir);
        }
        FileController::moveTempFile($img, $dir . '/' . $imgname);
        event(new Notify('Photo ajoutée à la question', 1));
        return response()->json([],201);

    }

    /**
     * @param string $formations_id
     * @return JsonResponse
     */
    public function getReponseOffFormations(string $formations_id): JsonResponse
    {
        $formations_id = (int) $formations_id;
        $formation = Formation::where('id', $formations_id)->first();
        foreach ($formation->GetResponses as $response){
            $response->GetUser;
        }
        return response()->json(['status'=>"OK", 'formation'=>$formation]);
    }

    /**
     * @param string response_id
     * @return JsonResponse
     */
    public function deleteResponseByID(string $response_id): JsonResponse
    {
        $response = FormationsResponse::where('id', $response_id)->first();
        $response->delete();
        return response()->json(['status'=>'OK',201]);
    }
}
