<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Http\Controllers\Controller;
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
            'access_stats'=>$grade->perm_23,
            'HS_facture'=>$grade->perm_24,
            'content_mgt'=>$grade->perm_25,
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
}
