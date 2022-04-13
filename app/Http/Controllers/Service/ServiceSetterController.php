<?php

namespace App\Http\Controllers\Service;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Models\User;
use App\Models\WeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceSetterController extends Controller
{

    public function setServiceByAdmin(Request $request, string $userid): \Illuminate\Http\JsonResponse
    {

        $user = User::where('id', $userid)->first();
        $this->authorize('setOtherService', $user);
        if(is_null($user->service) || is_null($user->name)){
            Notify::dispatch("Impossible de modier le service de cet utilisateur", 4, Auth::user()->id);
        }else{
            $logs = new LogsController();
            $logs->ServiceLogging($userid .  " was changed", Auth::user()->id);
            OperatorController::setService($user, true);
        }

        return response()->json(['status'=>'OK']);
    }

    public function addRows(): \Illuminate\Http\JsonResponse
    {
        $week =  date('W', time());
        $users = User::where('grade_id', '>', 1)->where('grade_id', '<', 10)->get();
        $dayservice = WeekService::where('week', $week)->get('user_id');
        $b = 0;
        $array = array();
        while($b < count($dayservice)){
            array_push($array, $dayservice[$b]->user_id);
            $b++;
        }
        $a = 0;
        $datas = array();
        while ($a < count($users)){
            if(!in_array($users[$a]->id, $array)){
                array_push($datas, ['week'=>$week, 'user_id'=>$users[$a]->id]);
            }
            $a++;
        }
        WeekService::insert($datas);
        return response()->json(['status'=>"OK"],201);
    }

    public function modifyTimeService(Request $request, string $userId): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'time'=>['required'],
            'action'=>['int','between:0,1']
        ]);

        $action = (int) $request->action;
        $time = (string) $request->time.':00';

        User::where('id', $userId)->firstOrFail();
        $WeekService= WeekService::where('user_id', $userId)->where('week_number', ServiceGetterController::getWeekNumber())->first();
        if($request->action){
            $WeekService->ajustement = \TimeCalculate::HoursAdd($WeekService->ajustement, $time);
            $WeekService->total = \TimeCalculate::HoursAdd($WeekService->total, $time);
        }else{$WeekService->ajustement = \TimeCalculate::HoursRemove($WeekService->ajustement, $time);
            $WeekService->total = \TimeCalculate::HoursRemove($WeekService->total, $time);
        }

        $WeekService->save();

        event(new Notify('Vous avez bien modifé le temps de service',1));
        return response()->json(['status'=>'OK'],201);
    }

    public function setservice(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        OperatorController::setService($user, false);
        $logs = new LogsController();
        $logs->ServiceLogging("service was changed", Auth::user()->id);
        return response()->json([
            'status'=>'OK',
            'user'=>$user,
        ]);
    }
}
