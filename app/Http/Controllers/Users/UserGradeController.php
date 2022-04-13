<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Service\OperatorController;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $grade = Grade::where('id',$id)->first();

        /*
        if($grade->having_matricule && !$user->dev && $id  != 1 && is_null($user->matricule)){
            $users = User::whereNotNull('matricule')->get();
            $matricules = array();
            foreach ($users as $usere){
                array_push($matricules, $usere->matricule);
            }
            $generated = null;
            while(is_null($generated) || in_array($generated, $matricules)){
                $generated = random_int(9, 99);
            }
            $user->matricule = $generated;
            $user->save();
            event(new Notify($user->name . ' a le matricule ' . $generated,1));
        }
        */
        if(Session::get('service')[0] === 'LSCoFD'){
            $user->fire_grade_id = $id;
        }
        if(Session::get('service')[0] === 'SAMS'){
            $user->medic_grade_id = $id;
        }
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

        if(is_null($user->service)){
            $user->grade = Grade::first();
        }else{
            if($user->service === 'SAMS'){
                $user->grade = Grade::where('id', $user->medic_grade_id)->first();
            }else{
                $user->grade = Grade::where('id', $user->fire_grade_id)->first();
            }
        }
        $user->sanctions = json_decode($user->sanctions);
        $user->materiel = json_decode($user->materiel);

        $fireGrade = Grade::where('id', $user->fire_grade_id)->first();
        $medicGrade = Grade::where('id', $user->medic_grade_id)->first();
        $user->fire_grade_name = $fireGrade->name;
        $user->medic_grade_name = $medicGrade->name;

        $collect = collect($user->grade->getAttributes());
        $collect = $collect->except(['service','name','power','discord_role_id','id']);
        foreach ($collect as $key => $item){
            $b = $user->grade->getAttributeValue($key);
            $user->grade[$key] = ($b === "1" || $b === true || $b === 1 );
        }

        return \response()->json(['status'=>'ok', 'user'=>$user]);
    }

    public function getGrade(): JsonResponse
    {
        \Gate::authorize('viewAny',Grade::class);
        $requester = User::where('id',Auth::user()->id)->first();
        if($requester->dev){
            $grades = Grade::orderBy('power','desc')->get();
        }else{
            $grades = Grade::where('service', Session::get('service')[0])->orderBy('power','desc')->get();
        }

        $grades->filter(function ($item){
            return \Gate::allows('view', $item);
        });
        return \response()->json(['status'=>'OK','grades'=>$grades]);
    }

    public function createGrade(Request $request): JsonResponse
    {
        \Gate::authorize('create',Grade::class);
        $grade = new Grade();
        $grade->name = 'nouveau grade';
        $grade->power = 0;
        $grade->service = Session::get('service')[0];
        $grade->save();

        return $this::getGrade();
    }

    public function updateGrade(Request $request){
        $grade = Grade::where('id', $request->grade['id'])->first();
        $exept = ['id', 'service', 'created_at','updated_at'];
        $updater = collect($request->grade)->except($exept);

        foreach ($updater  as $key => $value){
            $grade[$key] = $value;
        }
        $this->authorize('update', $grade);
        $grade->save();
        $users = User::where('medic_grade_id', $grade->id)->where('fire_grade_id')->get();
        foreach ($users as $user){
            UserUpdated::dispatch($user);
            Notify::dispatch('Modification de vos permission',1,Auth::user()->id);
        }

        Notify::dispatch('Mise à jour enregistrée',1,Auth::user()->id);
        return $this::getGrade();
    }

    public function deleteGrade(Request $request){
        \Gate::authorize('delete',Grade::class);
        $grade = Grade::where('id', $request->grade_id)->first();
        $users = User::where('medic_grade_id', $grade->id)->orWhere('fire_grade_id', $grade->id)->count();
        if($grade->default){
            Notify::dispatch('Ce grade ne peut pas être supprimé',3, Auth::user()->id);
            return $this->getGrade();
        }
        if($users != 0){
            Notify::dispatch('Ce grade ne peut pas être supprimé',3, Auth::user()->id);
            return $this->getGrade();
        }
        $grade->delete();
        Notify::dispatch('Grade supprimé',1, Auth::user()->id);
        return $this->getGrade();
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
        if( Session::get('service')[0] === "SAMS"){
            $user->medic_grade_id = 1;
            $user->crossService= false;
        }
        else{
            $user->fire_grade_id = 1;
            $user->crossService= false;
        }
        $user->bc_id = null;
        if($user->onService && $user->service == Session::get('service')[0]){
            OperatorController::setService($user, true);
        }
        $user->save();
        UserUpdated::broadcast($user);

        // mettre un embed de réinit du matériel

        Notify::dispatch($user->name .' ne fait plus partie du service',1, Auth::user()->id);
    }
}
