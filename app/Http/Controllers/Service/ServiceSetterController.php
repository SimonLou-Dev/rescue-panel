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
        $time = (string) $request->time;

        $user =User::where('name', $name)->firstOrFail();
        $WeekService= WeekService::where('user_id', $user->id)->where('week_number', ServiceGetterController::getWeekNumber())->first();
        if($action === 1){
            if($WeekService->ajustement == '00:00:00'){
                $WeekService->ajustement = '+'.$time.':00';
            }else{
                $ajustement = $WeekService->ajustement;
                $symbole = substr($ajustement, 0, 1-(strlen($ajustement)) );
                if($symbole == '+'){
                    $WeekService->ajustement = '+'.OperatorController::addTime(substr($ajustement,1), $time.':00');
                }else{
                    $calculate = $WeekService->ajustement = OperatorController::removeTime(substr($ajustement,1), $time.':00');
                    $calculate = str_replace(['+','-'], '', $calculate);
                    $ajustement = explode(':', $ajustement);
                    $base = explode(':',  $time.':00');
                    if($base[0] > $ajustement[0]){
                        $operator = '+';
                    }else if ($base[1] > $ajustement[1]){
                        $operator = '+';
                    } else if ($base[2] > $ajustement[2]){
                        $operator = '+';
                    }else {
                        $operator = '-';
                    }
                    if($calculate == '00:00:00'){
                        $operator = '';
                    }

                    $WeekService->ajustement = $operator.$calculate;
                }
            }
            $WeekService->total = OperatorController::addTime($WeekService->total, $time.':00');
        }else{


            if($WeekService->ajustement == '00:00:00'){
                $WeekService->ajustement = '-'.$time.':00';
            }else{
                $ajustement = $WeekService->ajustement;
                $symbole = substr($ajustement, 0, 1-(strlen($ajustement)) );
                if($symbole == '-'){
                    $WeekService->ajustement = '-'.OperatorController::addTime(substr($ajustement,1), $time.':00');
                }else{
                    $calculate = $WeekService->ajustement = OperatorController::removeTime(substr($ajustement,1), $time.':00');
                    $calculate = str_replace(['+','-'], '', $calculate);
                    $ajustement = explode(':', $ajustement);
                    $base = explode(':',  $time.':00');

                    if($base[0] > $ajustement[0]){
                        $operator = '-';
                    }else if ($base[1] > $ajustement[1]){
                        $operator = '-';
                    } else if ($base[2] > $ajustement[2]){
                        $operator = '-';
                    }else {
                        $operator = '+';
                    }
                    if($calculate == '00:00:00'){
                        $operator = '';
                    }

                    $WeekService->ajustement = $operator.$calculate;
                }
            }

            $WeekService->total = OperatorController::removeTime($WeekService->total, $time.':00');
        }
        $WeekService->save();

        event(new Notify('Vous avez bien modifé le temps de service',1));
        return response()->json(['status'=>'OK'],201);
    }
}
