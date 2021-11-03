<?php

namespace App\Http\Controllers\Service;

use App\Exporter\ExelPrepareExporter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LayoutController;
use App\Models\Service;
use App\Models\User;
use App\Models\WeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ServiceGetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public static function getWeekNumber(): int
    {
        $day = LayoutController::getdaystring();
        $date = (int) date('W', time());
        if($day == 'dimanche'){
            return $date +1;
        }else{
            return $date;
        }
    }

    public function getWeekServiceExel(string $week = null): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if(is_null($week)){
            $week = $this::getWeekNumber();
        }else{
            $week = (int) $week;
        }
        $users = User::where('grade_id', '>', 1)->where('grade_id', '<', 10)->orderByDesc('grade_id')->get();

        $column[] = array('Membre','grade', 'n° de compte', 'Remboursements', 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'ajustement', 'total');



        foreach ($users as $user){

            $service = $user->GetWeekServices->where('week_number', $week)->first();
            $remboursement=$user->GetRemboursement->where('week_number', $week)->first();

            if(isset($service)){
                $column[] = [
                    'Membre'=> $user->name,
                    'grade'=>$user->GetGrade->name,
                    'n° de compte'=>$user->compte,
                    'Remboursements'=> isset($remboursement) ? $remboursement->total : '0',
                    'dimanche'=>$service->dimanche,
                    'lundi'=>$service->lundi,
                    'mardi'=>$service->mardi,
                    'mercredi'=>$service->mercredi,
                    'jeudi'=>$service->jeudi,
                    'vendredi'=>$service->vendredi,
                    'samedi'=>$service->samedi,
                    'ajustement'=>$service->ajustement,
                    'total'=>$service->total,
                ];
            }else{
                $column[] = [
                    'Membre'=> $user->name,
                    'grade'=>$user->GetGrade->name,
                    'n° de compte'=>$user->compte,
                    'Remboursements'=> isset($remboursement) ? $remboursement->total : '0' ,
                    'dimanche'=>'00:00:00',
                    'lundi'=>'00:00:00',
                    'mardi'=>'00:00:00',
                    'mercredi'=>'00:00:00',
                    'jeudi'=>'00:00:00',
                    'vendredi'=>'00:00:00',
                    'samedi'=>'00:00:00',
                    'ajustement'=>'00:00:00',
                    'total'=>'00:00:00',
                ];
            }
        }
        $export = new ExelPrepareExporter($column);

        return Excel::download((object)$export, 'RemboursementsServicesSemaine'. $week .'.xlsx');


    }

    public function getUserService(): \Illuminate\Http\JsonResponse
    {
        $date = $this::getWeekNumber();
        if($date == 1 ){
            $week = WeekService::where('week_number', '=', 1)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else if($date == 2){
            $week = WeekService::where('week_number', '<=', 2)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else if($date == 3){
            $week = WeekService::where('week_number', '<=', 3)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else{
            $date = $date -3;
            $week = WeekService::where('week_number', '>', $date)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }
        $userserivce= Service::where('user_id', Auth::user()->id)->orderBy('id','desc')->take(20)->get();
        return response()->json([
            'status'=>'ok',
            'week'=>$week,
            'services'=>$userserivce,
        ]);

    }

    public function getAllservice(int $semaine = NULL): \Illuminate\Http\JsonResponse
    {
        $max = $this::getWeekNumber();
        if($semaine){
            $date= (int) $semaine;
        }else{
            $date = $this::getWeekNumber();
        }
        $service = WeekService::where('week_number', $date)->orderBy('id','asc')->get();
        $a= 0;
        while($a < count($service)){
            $service[$a]->GetUser->GetGrade;
            $a++;
        }
        return response()->json([
            'service'=>$service,
            'maxweek'=>$max,
        ]);
    }
}
