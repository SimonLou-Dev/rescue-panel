<?php

namespace App\Http\Controllers\Service;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeekService;
use Illuminate\Http\Request;

class ServiceSetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function setServiceByAdmin(Request $request, string $userid): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', $userid)->first();
        OperatorController::setService($user, true);

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

    public function modifyTimeService(Request $request): \Illuminate\Http\JsonResponse
    {
        $name = (string) $request->name;
        $action = (int) $request->action;
        $time = (string) $request->time.':00';

        $user =User::where('name', $name)->firstOrFail();
        $WeekService= WeekService::where('user_id', $user->id)->where('week_number', ServiceGetterController::getWeekNumber())->first();
        $WeekService->ajustement = OperatorController::ajustementCalculator($WeekService->ajustement, $time, $action === 1);
        if($action === 1){
            $WeekService->total = OperatorController::addTime($WeekService->total, $time);
        }else{
            $WeekService->total = OperatorController::removeTime($WeekService->total, $time);
        }


        $WeekService->save();

        event(new Notify('Vous avez bien modifé le temps de service',1));
        return response()->json(['status'=>'OK'],201);
    }
}
