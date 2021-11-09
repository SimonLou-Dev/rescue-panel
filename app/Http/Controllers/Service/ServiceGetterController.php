<?php

namespace App\Http\Controllers\Service;

use App\Exporter\ExelPrepareExporter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LayoutController;
use App\Models\Prime;
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

        $column[] = array('Membre','grade', 'n° de compte','primes', 'Remboursements', 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'ajustement', 'total');



        foreach ($users as $user){

            $service = $user->GetWeekServices->where('week_number', $week)->first();
            $remboursement=$user->GetRemboursement->where('week_number', $week)->first();
            $primes = Prime::where('user_id', $user->id)->where('week_number', $week)->get();
            $total = 0;
            foreach ($primes as $prime){
                $total = $total + $prime->getItem->montant;
            }

            if(isset($service)){
                $column[] = [
                    'Membre'=> $user->name,
                    'grade'=>$user->GetGrade->name,
                    'n° de compte'=>$user->compte,
                    'primes'=>$total,
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
                    'primes'=>$total,
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
        $reqweek = WeekService::where('week_number', $date)->orderBy('id','asc')->where('user_id', Auth::id())->take(5)->get();
        $weeks = WeekService::orderBy('id','desc')->where('user_id', Auth::id())->take(5)->get();

        $usercount = Service::where('user_id', Auth::user()->id)->count();
        if($usercount > 10){
            $usercount = $usercount - 10;
        }
        $userserivces= Service::where('user_id', Auth::user()->id)->orderBy('id','asc')->skip((int) $usercount)->take(10)->get();
        $weeknumber = array();
        $weektotal = array();
        foreach ($weeks as $week){
            array_push($weeknumber, $week->week_number);
            if(!is_null($week->total)){
                $splited = explode(':',$week->total);
                $week->total = $splited[0] + ($splited[1] > 30 ? 1 : 0);
            }
            array_push($weektotal, $week->total);
        }
        $usersserviceid = array();
        $userserivcetime = array();
        foreach ($userserivces as $userserivce){
            array_push($usersserviceid, $userserivce->id);
            if(!is_null($userserivce->total)){
                $splited = explode(':',$userserivce->total);
                $userserivce->total = $splited[0] + ($splited[1] > 30 ? 1 : 0);
            }
            array_push($userserivcetime, $userserivce->total);
        }


        return response()->json([
            'status'=>'ok',
            'week'=>$reqweek,
            'services'=>$userserivces,
            'weekgraph'=>[$weeknumber, $weektotal],
            'servicegraph'=>[$usersserviceid, $userserivcetime]
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
