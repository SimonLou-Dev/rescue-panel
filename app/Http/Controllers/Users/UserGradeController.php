<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Service\OperatorController;
use App\Http\Controllers\ServiceController;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGradeController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @param int $userid
     * @return JsonResponse
     */
    public function setusergrade(Request $request, int $id, int $userid): JsonResponse
    {
        $user= User::where('id', $userid)->first();
        $requester = User::where('id', Auth::user()->id)->first();
        if($requester->grade_id < 10){
            if($user->id == $requester->id){
                event(new Notify('Impossible de modifier son propre grade ! ',4));
                return \response()->json(['status'=>'OK']);
            }
            if($id >= $requester->grade_id){
                event(new Notify('Impossible de mettre un grade plus haut que le siens ! ',4));
                return \response()->json(['status'=>'OK']);
            }
        }
        if($id == 1){
            $this::removegradeFromuser($userid);
        }
        if($user->grade_id == 1 && $id != 1){
            $users = User::whereNotNull('matricule')->where('grade_id', '>',1)->where('grade_id', '<',12)->get();
            $matricules = array();
            foreach ($users as $usere){
                array_push($matricules, $usere->matricule);
            }
            $generated = null;
            while(is_null($generated) || array_search($generated, $matricules)){
                $generated = random_int(10, 99);
            }
            $user->matricule = $generated;
            $user->save();
            event(new Notify($user->name . ' a le matricule ' . $generated,1));
        }
        $user->grade_id = $id;
        $user->save();
        event(new Notify('Le grade a été bien changé ! ',1));
        return \response()->json(['status'=>'OK']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function GetUserPerm(Request $request): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        $grade = Grade::where('id', $user->grade_id)->first();
        $perm = [
            'acces'=>$grade->perm_0,
            'HS_rapport'=>$grade->perm_1,
            'HS_dossier'=>$grade->perm_2,
            'HS_BC'=>$grade->perm_3,
            'factures_PDF'=>$grade->perm_4,
            'add_factures'=>$grade->perm_5,
            'rapport_create'=>$grade->perm_6,
            'add_BC'=>$grade->perm_7,
            'remboursement'=>$grade->perm_8,
            'infos_edit'=>$grade->perm_9,
            'vol'=>$grade->perm_10,
            'rapport_horaire'=>$grade->perm_11,
            'service_modify'=>$grade->perm_12,
            'time_modify'=>$grade->perm_13,
            'perso_list'=>$grade->perm_14,
            'set_pilot'=>$grade->perm_15,
            'edit_perm'=>$grade->perm_16,
            'post_annonces'=>$grade->perm_17,
            'logs_acces'=>$grade->perm_18,
            'validate_forma'=>$grade->perm_19,
            'create_forma'=>$grade->perm_20,
            'forma_publi'=>$grade->perm_21,
            'forma_delete'=>$grade->perm_22,
            'grade_modify'=>$grade->perm_23,
            'HS_facture'=>$grade->perm_24,
            'content_mgt'=>$grade->perm_25,

            'view_member_sheet'=>$grade->perm_26,
            'set_discordid'=>$grade->perm_27,
            'sanction_MAP'=>$grade->perm_28,
            'sanction_exclu'=>$grade->perm_29,
            'sanction_warn'=>$grade->perm_30,
            'sanction_degrade'=>$grade->perm_31,
            'sanction_remove'=>$grade->perm_32,
            'modify_material'=>$grade->perm_33,
            'membersheet_note'=>$grade->perm_34,
            'HS_poudre'=>$grade->perm_35,
            'HS_poudre_history'=>$grade->perm_36,
            'timeserviceupdate_request'=>$grade->perm_37,
            'primesupdate_request'=>$grade->perm_38,
            'useless'=>$grade->perm_39,


            'user_id'=>$user->id
        ];
        return \response()->json(['status'=>'ok', 'perm'=>$perm, 'user'=>$user]);
    }

    public function getAllGrades(): JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        $grades = Grade::where('id', '<=', $user->grade_id)->get();
        return \response()->json(['status'=>'OK','grades'=>$grades]);
    }

    public function changePerm(string $perm, string $grade_id): JsonResponse
    {
        $grade = Grade::where('id', $grade_id)->first();
        $grade[$perm] = !$grade[$perm];
        $grade->save();
        event(new Notify('Vous avez changé une permissions',1));
        return \response()->json(['status'=>'OK'],201);
    }

    public static function removegradeFromuser(int $id){
        $user = User::where('id', $id)->first();
        $user->materiel = null;
        $user->matricule = null;
        $user->grade_id = 1;
        $user->bc_id = null;
        if($user->service){
            OperatorController::setService($user, true);
        }
        $user->save();

        // mettre un embed de réinit du matériel

        event(new Notify($user->name .' ne fait plus partie du service',1));
    }
}
