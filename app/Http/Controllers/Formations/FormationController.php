<?php

namespace App\Http\Controllers\Formations;

use App\Events\Notify;
use App\Http\Controllers\Controller;
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





}
