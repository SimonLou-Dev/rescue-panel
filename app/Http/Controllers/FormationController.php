<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\Formation;
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
    public function getUsersCertifications(): JsonResponse
    {
        $forma = Formation::all();
        $grades = Grade::where('perm_0', true)->get();
        $list = array();
        foreach ($grades as $grade){
            array_push($list, $grade->id);
        }
        $users = User::whereIn('grade_id', $list)->get();
        foreach ($users as $user){
            $user->GetCertifications;

        }
        return response()->json([
            'status'=>'OK',
            'users'=>$users,
            'certifs'=>$forma,
        ]);
    }

    /**
     * @param string $certif_id
     */
    public function changeUserCertification(string $certif_id){
        $certif_id = (int) $certif_id;
        // a faire
    }

    /**
     * @param string $formation_id
     */
    public function getFormationByIdAdmin(string $formation_id){
        $formation_id = (int) $formation_id;
        // a faire
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
     */
    public function updateFormation(Request $request, string $formation_id){
        $formation_id = (int) $formation_id;
        // a faire
    }

    /**
     * @param Request $request
     */
    public function addQuestion(Request $request){
        // a faire
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
     */
    public function updateQuestion(Request $request, string $question_id){
        $question_id = (int) $question_id;
        // a faire
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
     */
    public function getFormationById(string $formation_id){
        $formation_id = (int) $formation_id;
        // a faire
    }

    /**
     * @param string $question_id
     */
    public function getQuestioById(string $question_id){
        $question_id = (int) $question_id;
        // a faire
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
