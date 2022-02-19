<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Events\UserUpdated;
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
        UserUpdated::broadcast($user);
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
        if($user->service === "SAMS"){
            $user->grade = $user->GetMedicGrade;
        }else if($user->service === "LSCoFD"){
            $user->grade = $user->GetFireGrade;
        }
        return \response()->json(['status'=>'ok', 'user'=>$user]);
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
        UserUpdated::broadcast($user);

        // mettre un embed de réinit du matériel

        event(new Notify($user->name .' ne fait plus partie du service',1));
    }
}
