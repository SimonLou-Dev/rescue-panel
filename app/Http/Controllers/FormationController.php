<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\Certification;
use App\Models\Formation;
use App\Models\FormationsQuestion;
use App\Models\Grade;
use App\Models\ListCertification;
use App\Models\User;
use http\Env\Response;
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
     */
    public function changeFormationVisibility(string $formation_id){
        // a faire
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
        $img = $request->get('img');
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
            $max_try = 0;
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
            $timer = (int) ($time_str[0]*3600) + ($time_str[1]*60);
        }

        $imgname = time() . '.' . explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];
        $path = "/storage/formations/front_img/";

        $formation = new Formation();
        //infos bloballes
        $formation->name = $name;
        $formation->public = false;
        $formation->creator_id = Auth::user()->id;
        $formation->desc = $desc;
        $formation->image = $imgname;
        //essai
        $formation->unic_try = $unic_try;
        $formation->can_retry_later = $time_btw;
        $formation->try = $max_try;
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
        $dir = public_path($path . $formation->id);
        mkdir($dir);
        event(new Notify('Vous avez ajouté une formation', 1));
        Image::make($img)->resize(960,540)->save($dir . '/' . $imgname);

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
        $img = $request->get('img');
        if(!isset($img)) {
            $img = 'undefined';
        }
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
            $max_try = 0;
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
            $timer = (int) ($time_str[0]*3600) + ($time_str[1]*60);
        }

        if($img != 'undefined'){
            $imgname = time() . '.' . explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];
            $path = "/storage/formations/front_img/";
        }

        $formation = Formation::where('id', $formation_id)->first();
        //infos bloballes
        $formation->name = $name;
        $formation->public = false;
        $formation->creator_id = Auth::user()->id;
        $formation->desc = $desc;
        if($img != 'undefined') {
            $formation->image = $imgname;
        }
        //essai
        $formation->unic_try = $unic_try;
        $formation->can_retry_later = $time_btw;
        $formation->try = $max_try;
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
        if($img != 'undefined'){
            $dir = public_path($path . $formation->id);
            mkdir($dir);
            Image::make($img)->resize(960,540)->save($dir . '/' . $imgname);
        }

        event(new Notify('Vous avez sauvegarder la formations', 1));
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
        $img = $request->get('img');
        $formation_id = (int) $formation_id;
        $formation = Formation::where('id', $formation_id)->first();
        if(!isset($formation)){
            return \response()->json([],500);
        }
        $imgname = time() . '.' . explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];
        $path = "/storage/formations/question_img/";
        $question = new FormationsQuestion();
        $question->formation_id = $formation->id;
        $responses=$request->responses;
        $a=0;
        foreach ($responses as $response){
            if($response['active']){
                $a++;
            }
        }
        $question->responses = json_encode($responses);
        $question->name = $request->name;
        $question->type = ($a > 1 ? 'choix multiple' : 'choix unique');
        $question->max_note = $a;
        $formation->max_note= $formation->max_note+$a;
        $question->desc = $request->description;
        $question->correction = $request->correction;
        $question->img = $imgname;
        $question->save();
        $formation->save();
        $dir = public_path($path . $formation->id);
        mkdir($dir);
        event(new Notify('Vous avez ajouté une question', 1));
        Image::make($img)->resize(960,540)->save($dir . '/' . $imgname);
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
        $img = $request->get('img');
        if(!isset($img)) {
            $img = 'undefined';
        }
        $question = FormationsQuestion::where('id', $question_id)->first();
        $formation = Formation::where('id', $question->GetFormation->id)->first();
        if(!isset($question)){
            return \response()->json([],500);
        }
        if($img != 'undefined'){
            $imgname = time() . '.' . explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];
            $path = "/storage/formations/question_img/";
        }
        $responses=$request->responses;
        $a=0;
        foreach ($responses as $response){
            if($response['active']){
                $a++;
            }
        }
        $question->responses = json_encode($responses);
        $question->name = $request->name;
        $question->type = ($a > 1 ? 'choix multiple' : 'choix unique');
        $question->desc = $request->description;
        $question->correction = $request->correction;
        if($img != 'undefined') {
            $question->img = $imgname;
        }
        $formation->max_note = $formation->max_note - $question->max_note;
        $question->max_note = $a;
        $formation->max_note = $formation->max_note + $a;
        $formation->save();
        $question->save();
        if($img != 'undefined') {
            $dir = public_path($path . $question->GetFormation->id);
            mkdir($dir);
            Image::make($img)->resize(960,540)->save($dir . '/' . $imgname);
        }
        event(new Notify('Vous avez ajouté une question', 1));
        return \response()->json(['status'=>'OK'],201);
    }

    /**
     * @param string $formation_id
     */
    public function deleteFormationById(string $formation_id){
        $formation_id = (int) $formation_id;
    }

    public function getFormations(){
        // a faire
    }

    /**
     * @param string $formation_id
     * @return JsonResponse
     */
    public function getFormationById(string $formation_id): JsonResponse
    {
        $formation_id = (int) $formation_id;
        $formation = Formation::where('id', $formation_id);
        return response()->json([
            'status'=>'OK',
            'formation'=>$formation,
            'responses'=>$formation->GetQuestions]);
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
     */
    public function saveResponseState(Request $request, string $question_id){
        $question_id = (int) $question_id;
        // a faire
    }

    /**
     * @param string $response_id
     */
    public function deleteResponseStateById(string $response_id){
        $response_id = (int) $response_id;
        // a faire
    }

    /**
     * @param string $formation_id
     */
    public function getFinalDatas(string $formation_id){
        $formation_id = (int) $formation_id;
        // a faire
    }


}
