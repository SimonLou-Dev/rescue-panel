<?php

namespace App\Http\Controllers\Formations;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationsQuestion;
use App\Models\FormationsResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
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

}
