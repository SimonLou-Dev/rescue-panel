<?php

namespace App\Http\Controllers\Formations;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Models\Formation;
use App\Models\FormationsQuestion;
use App\Models\FormationsResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
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
     * @param Request $request
     * @param string $question_id
     * @return JsonResponse
     */
    public function postQuestionImage(Request $request, string $question_id): JsonResponse
    {
        $question = FormationsQuestion::where('id', $question_id)->first();
        $img = $request->get('image');
        $imgname = Auth::user()->id . '_' . time() . '.' . explode('.', $img)[1];
        $path = storage_path("formations/question_img/".$question->GetFormation->id.'/'. $question_id);
        $question->img = $imgname;
        $question->save();
        if(!is_dir($path)){
            mkdir($path);
        }
        FileController::moveTempFile($img, $path . '/' . $imgname);
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
        $path = storage_path('public/formations/front_img/'.$formation_id);
        $formation->image = $imgname;
        $formation->save();
        if(!is_dir($path)){
            mkdir($path);
        }
        FileController::moveTempFile($img, $path . '/' . $imgname);
        event(new Notify('Photo ajoutée à la question', 1));
        return response()->json([],201);

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
