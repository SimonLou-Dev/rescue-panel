<?php

namespace App\Http\Controllers\Dev;

use App\Events\Notify;
use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request){
        $this->authorize('dev');
        if($request->query('query') == ''){
            $users = User::withTrashed()->get();
        }else{
            $users = User::search($request->query('query'))->withTrashed()->get();
        }
        $medicGrade = Grade::where('service', 'SAMS')->orWhere('service', 'staff')->orWhere('service', 'dev')->get();
        $fireGrade = Grade::where('service', 'LSCoFD')->orWhere('service', 'staff')->orWhere('service', 'dev')->get();

        return response()->json([
            'users'=>$users,
            'medic'=>$medicGrade,
            'fire'=>$fireGrade,
        ]);
    }

    public function setStaff(Request $request, string $userId){
        $this->authorize('dev');
        $user = User::where('id',$userId)->first();
        if($user->moderator){
            $user->moderator = false;
            $user->medic_grade_id = 1;
            $user->fire_grade_id = 1;
            $user->crossService = false;
        }else{
            $user->moderator = true;
            $user->medic_grade_id = 6;
            $user->fire_grade_id = 6;
            $user->crossService = true;
        }
        $user->save();
        event(new UserUpdated($user));
        return response()->json([],202);
    }

    public function setDev(Request $request, string $userId){
        $this->authorize('dev');
        $user = User::where('id',$userId)->first();
        if($user->dev){
            $user->dev = false;
            $user->medic_grade_id = 1;
            $user->fire_grade_id = 1;
            $user->crossService = false;
        }else{
            $user->dev = true;
            $user->medic_grade_id = 7;
            $user->fire_grade_id = 7;
            $user->crossService = true;
        }
        $user->save();
        event(new UserUpdated($user));
        return response()->json([],202);
    }

    public function deleteUser(Request $request, string $userId){
        $this->authorize('dev');
        $user = User::withTrashed()->where('id',$userId)->first();
        if(is_null($user->deleted_at)){
            $user->delete();
        }else{
            $user->restore();
        }

        return response()->json([],202);
    }

    public function setService(Request $request, string $userId, string $service){
        $this->authorize('dev');
        $user = User::where('id',$userId)->first();
        if($service ==='SAMS'){
            $user->medic = !$user->medic;
        }else{
            $user->fire = !$user->fire;
        }
        $user->save();
        event(new UserUpdated($user));
        return response()->json([],202);
    }

    public function setGrade(Request $request, string $userId, string $service, string $gradeId){
        $this->authorize('dev');
        $user = User::where('id',$userId)->first();

        if($service ==='SAMS'){
            $user->medic_grade_id = $gradeId;
        }else{
            $user->fire_grade_id = $gradeId;
        }
        $user->save();
        event(new UserUpdated($user));
        return response()->json([],202);
    }

    public function setCrossService(Request $request, string $userId){
        $this->authorize('dev');
        $user = User::where('id',$userId)->first();
        $user->crossService = !$user->crossService;
        $user->save();
        event(new UserUpdated($user));
        return response()->json([],202);
    }
}
