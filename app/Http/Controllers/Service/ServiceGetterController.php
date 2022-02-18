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
        $users = User::where('medic', true)->orWhere('fire', true)->get();
        $users = $users->filter(function ($item, $key){
            return \Gate::allows('view', $item);
        });

        $column[] = array('Membre','grade', 'n° de compte','primes', 'Remboursements', 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'ajustement', 'total');


        foreach ($users as $user){

            $service = $user->GetWeekServices->where('week_number', $week)->first();
            $remboursement=$user->GetRemboursement->where('week_number', $week)->first();
            $primes = Prime::where('user_id', $user->id)->where('week_number', $week)->get();
            $total = 0;
            foreach ($primes as $prime){
                $total = $total + $prime->getItem->montant;
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
                    'grade'=>$grade->name,
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
        $weekcount = WeekService::where('user_id', Auth::id())->count();
        $weeks = WeekService::where('user_id', Auth::id())->skip(($weekcount -5))->take(5)->get();

        $usercount = Service::where('user_id', Auth::user()->id)->count();
        if($usercount > 10){
            $usercount = $usercount - 10;
        }else{
            $usercount = 0;
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

    public function getAllservice(Request $request, int $semaine = NULL): \Illuminate\Http\JsonResponse
    {
        $this->authorize('viewRapportHoraire', User::class);

        $max = $this::getWeekNumber();
        if(isset($semaine)){
            $date= (int) $semaine;
        }else{
            $date = $this::getWeekNumber();
        }

        $service = WeekService::search($request->query('query'))->get()->reverse();
        $queryPage = (int) $request->query('page');
        $readedPage = ($queryPage ?? 1) ;
        $readedPage = (max($readedPage, 1));
        $user = User::where('id', Auth::user()->id)->first();

        $forgetable = [];

        for($a = 0; $a < $service->count(); $a++){
            $searchedItem = $service[$a];
            if($searchedItem->service !== $user->service){
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

            $remboursements = WeekRemboursement::where('week_number', $date)->where('user_id', $user->id);
            if($remboursements->count() === 1){
                $item->remboursement =  $remboursements->first()->total;
            }
            $primes = Prime::where('week_number', $date)->where('user_id', $user->id)->get();
            if($primes->count() > 0){
                foreach ($primes as $prime){
                    $item->prime += $prime->getItem->montant;
                }
            }
        }

        $finalList = $service->skip(($readedPage-1)*20)->take(20);

        $url = $request->url() . '?query='.urlencode($request->query('query')).'&page=';
        $totalItem = $service->count();
        $valueRounded = ceil($totalItem / 5);
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
}
