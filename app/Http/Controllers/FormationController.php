<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Grade;
use App\Models\ListCertification;
use App\Models\User;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function getUsersCertifications(){
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
     */
    public function postFormation(Request $request){
        // a faire
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
