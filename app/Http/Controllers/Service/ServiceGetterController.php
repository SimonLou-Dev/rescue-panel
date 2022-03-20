<?php

namespace App\Http\Controllers\Service;

use App\Exporter\ExelPrepareExporter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LayoutController;
use App\Models\Pathology;
use App\Models\Prime;
use App\Models\Service;
use App\Models\User;
use App\Models\WeekRemboursement;
use App\Models\WeekService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ServiceGetterController extends Controller
{

    public static function getWeekNumber(int $a = null): int
    {
        if(is_null($a))$a = time();
        $day = LayoutController::getdaystring($a);
        $date = (int) date('W', $a);
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
        $users = User::all();
        $users = $users->filter(function ($item, $key){
            $item->grade = $item->getUserGradeInService();
            $prq = \Gate::allows('view', $item) && (!is_null($item->service)) && ($item->grade->name !== 'default') && ($item->grade->name !== "staff");
            if(($item->isInFireUnit() && Session::get('service')[0] === 'LSCoFD') || ($item->isInMedicUnit() && Session::get('service')[0] === 'SAMS')){
                return $prq;
            }
            return false;
        });

        $column[] = array('Membre','grade', 'n° de compte','primes', 'Remboursements', 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'ajustement', 'total');


        foreach ($users as $user){

            $service = $user->GetWeekServices->where('week_number', $week)->where('service', Session::get('service')[0])->first();
            $remboursement=$user->GetRemboursement->where('week_number', $week)->where('service', Session::get('service')[0])->first();
            $primes = Prime::where('user_id', $user->id)->where('service', Session::get('service')[0])->where('week_number', $week)->get();
            $total = 0;
            foreach ($primes as $prime){
                $total = $total + $prime->montant;
            }
            if(Session::get('service')[0] == 'SAMS'){
                $grade = $user->GetMedicGrade;
            }else if(Session::get('service')[0] == 'LSCoFD'){
                $grade = $user->GetFireGrade;
            }

            if(isset($service)){
                $column[] = [
                    'Membre'=> $user->name,
                    'grade'=>$grade->name,
                    'n° de compte'=>$user->compte,
                    'primes'=>$total !== 0 ? $total : '0',
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
                    'grade'=>$grade->name,
                    'n° de compte'=>$user->compte,
                    'primes'=>$total !== 0 ? $total : '0',
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
        $weekcount = WeekService::where('user_id', Auth::id())->where('service', Session::get('service')[0])->count();
        $weeks = WeekService::where('user_id', Auth::id())->skip(($weekcount -5))->where('service', Session::get('service')[0])->take(5)->get();


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
        $thisWeek = WeekService::where('user_id', Auth::id())->where('service', Session::get('service')[0])->where('week_number',$date);
        $thisExist = $thisWeek->count() > 0;

        $graphicX = array('dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi');
        if($thisExist){
            $thisWeek = $thisWeek->first();
            $graphicYdata = array($thisWeek->dimanche, $thisWeek->lundi, $thisWeek->mardi,$thisWeek->mercredi,$thisWeek->jeudi,$thisWeek->vendredi,$thisWeek->samedi);
            $dayNbr = 0;
            if(LayoutController::getdaystring() === 'dimanche') $dayNbr = 1;
            if(LayoutController::getdaystring() === 'lundi') $dayNbr = 2;
            if(LayoutController::getdaystring() === 'mardi') $dayNbr = 3;
            if(LayoutController::getdaystring() === 'mercredi') $dayNbr = 4;
            if(LayoutController::getdaystring() === 'jeudi') $dayNbr = 5;
            if(LayoutController::getdaystring() === 'vendredi') $dayNbr = 6;
            if(LayoutController::getdaystring() === 'samedi') $dayNbr = 7;

            $graphicY = array(null, null,null,null,null,null,null);
            for ($a =0; $a < $dayNbr; $a++){
                if($graphicYdata != '00:00:00' && $graphicYdata != 'absent(e)'){
                    $splited = explode(':',$graphicYdata[$a]);
                    $graphicY[$a] = $splited[0] + ($splited[1] > 30 ? 1 : 0);
                }

            }


        }else{
            $graphicY = array(null, null,null,null,null,null,null);
        }



        return response()->json([
            'status'=>'ok',
            'weekgraph'=>[$weeknumber, $weektotal],
            'thisWeek'=>[
                'total'=>$thisExist ? $thisWeek->total : '00:00:00',
                'ajustement'=>$thisExist ? $thisWeek->ajustement : '00:00:00',
                'graphic'=>[$graphicX, $graphicY]
            ]
        ]);

    }

    public function getAllservice(Request $request, int $semaine = NULL): \Illuminate\Http\JsonResponse
    {
        $this->authorize('viewRapportHoraire', User::class);

        $max = $this::getWeekNumber();
        if(isset($semaine)){
            $date= (int) $semaine;
        }else{
            $date = $this::getWeekNumber();
        }

        if(is_null($request->query('query'))){
            $service = WeekService::orderByDesc('id')->get();
        }else{
            $service = WeekService::search($request->query('query'))->get()->reverse();
        }

        $queryPage = (int) $request->query('page');
        $readedPage = ($queryPage ?? 1) ;
        $readedPage = (max($readedPage, 1));
        $user = User::where('id', Auth::user()->id)->first();

        $forgetable = [];

        for($a = 0; $a < $service->count(); $a++){
            $searchedItem = $service[$a];
            $searchedItem->grade = $searchedItem->GetUser->getUserGradeInService();
            if($searchedItem->service !== $user->service || $searchedItem->grade->name === "staff"){
                array_push($forgetable, $a);
            }

            if($searchedItem->week_number !== $date){
                array_push($forgetable, $a);
            }
            if(!\Gate::allows('view', $searchedItem->GetUser)){
                array_push($forgetable, $a);
            }
        }

        foreach ($forgetable as $forget){
            $service->forget($forget);
        }
        foreach ($service as $item){
            $user = $item->GetUser;
            $item->remboursement = 0;
            $item->prime = 0;

            $remboursements = WeekRemboursement::where('week_number', $date)->where('user_id', $user->id)->where('service', Session::get('service')[0]);
            if($remboursements->count() === 1){
                $item->remboursement =  $remboursements->first()->total;
            }
            $primes = Prime::where('week_number', $date)->where('user_id', $user->id)->where('service', Session::get('service')[0])->get();

            if($primes->count() > 0){
                foreach ($primes as $prime){
                    $item->prime += $prime->montant;
                }
            }
        }

        $finalList = $service->skip(($readedPage-1)*20)->take(20);

        $url = $request->url() . '?query='.urlencode($request->query('query')).'&page=';
        $totalItem = $service->count();
        $valueRounded = ceil($totalItem / 20);
        $maxPage = (int) ($valueRounded == 0 ? 1 : $valueRounded);
        //Creation of Paginate Searchable result
        $array = [
            'current_page'=>$readedPage,
            'last_page'=>$maxPage,
            'data'=> $finalList,
            'next_page_url' => ($readedPage === $maxPage ? null : $url.($readedPage+1)),
            'prev_page_url' => ($readedPage === 1 ? null : $url.($readedPage-1)),
            'total' => $totalItem,
        ];

        return response()->json([
            'service'=>$array,
            'maxweek'=>$max,
        ]);
    }

    public function getUserOnServiceInUnit(Request $request){
        $users = User::where('service', Session::get('service')[0])->where('OnService', true)->get();
        $array = [];
        foreach ($users as $user){
            array_push($array, ['name'=>$user->name, 'id'=>$user->id]);
        }
        return response()->json([
            'users'=>$array
        ]);
    }
}
