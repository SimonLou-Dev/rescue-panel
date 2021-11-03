<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\DayService;
use App\Models\Service;
use App\Models\Services;
use App\Models\User;
use App\Models\WeekRemboursement;
use App\Models\WeekService;
use App\Exporter\ExelPrepareExporter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use phpDocumentor\Reflection\Types\Null_;
use function Psy\debug;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
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

    public function setServiceByAdmin(Request $request, string $userid): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', $userid)->first();
        $this->setService($user, true);

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
        $WeekService= WeekService::where('user_id', $user->id)->where('week_number', $this::getWeekNumber())->first();
        if($action === 1){
            if($WeekService->ajustement == '00:00:00'){
                $WeekService->ajustement = '+'.$time.':00';
            }else{
                $ajustement = $WeekService->ajustement;
                $symbole = substr($ajustement, 0, 1-(strlen($ajustement)) );
                if($symbole == '+'){
                    $WeekService->ajustement = '+'.$this::addTime(substr($ajustement,1), $time.':00');
                }else{
                    $calculate = $WeekService->ajustement = $this::removeTime(substr($ajustement,1), $time.':00');
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
            $WeekService->total = $this::addTime($WeekService->total, $time.':00');
        }else{


            if($WeekService->ajustement == '00:00:00'){
                $WeekService->ajustement = '-'.$time.':00';
            }else{
                $ajustement = $WeekService->ajustement;
                $symbole = substr($ajustement, 0, 1-(strlen($ajustement)) );
                if($symbole == '-'){
                    $WeekService->ajustement = '-'.$this::addTime(substr($ajustement,1), $time.':00');
                }else{
                    $calculate = $WeekService->ajustement = $this::removeTime(substr($ajustement,1), $time.':00');
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

            $WeekService->total = $this::removeTime($WeekService->total, $time.':00');
        }
        $WeekService->save();

        event(new Notify('Vous avez bien modifé le temps de service',1));
        return response()->json(['status'=>'OK'],201);
    }

    public static function setService(User $user, bool $admin): bool
    {
        if ($user->last_service_update != null){
            if($user->last_service_update + 120 > now()->timestamp) {
                event(new Notify('Stop SPAMMMMMMM !!!!! ;)', 4));
                return true;
            }
        }

        $user->last_service_update = now()->timestamp;

        if($user->service){
            $user->service = false;
            $user->serviceState = null;
            $user->save();
            $service = Service::where('user_id', $user->id)->whereNull('Total')->first();
            $start = date_create($service->started_at);
            $interval = $start->diff(date_create(date('Y-m-d H:i:s', time())));
            $diff = $interval->d*24 + $interval->h;
            $formated = $diff . ':' . $interval->format('%i:%S');
            $week =  ServiceController::getWeekNumber();
            $service->ended_at = date('H:I:s', time());
            $service->total = $formated;

            $service->save();

            $WeekService = WeekService::where('week_number', $week)->where('user_id', $user->id);
            if($WeekService->count() == 1){
                $WeekService = $WeekService->first();
                $total = $WeekService->total;
                $day = $WeekService[LayoutController::getdaystring()];
                $service = new ServiceController();
                $WeekService->total = $service->addTime($formated, $total);
                $WeekService[LayoutController::getdaystring()] = $service->addTime($formated, $day);
                $WeekService->save();

            }else{
                $WeekService = new WeekService();
                $WeekService->user_id = $user->id;
                $WeekService['week_number'] = $week;
                $WeekService[LayoutController::getdaystring()] = $formated;
                $WeekService->total = $formated;
                $WeekService->save();
            }
            $user->save();
            $formated = explode(':', $formated);
            if($admin){
                Http::post(env('WEBHOOK_SERVICE') ,[
                    'username'=> "BCFD - MDT",
                    'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
                    'embeds'=>[
                        [
                            'title'=>'Fin de service de ' . $user->name,
                            'description'=> 'temps de service : ' . $formated[0]. ' h et ' . $formated[1]. ' min(s)',
                            'color'=>'15158332',
                            'footer'=>[
                                'text'=>'stoppé par : ' . Auth::user()->name
                            ]
                        ]
                    ]
                ]);
            }else{
                Http::post(env('WEBHOOK_SERVICE'),[
                    'username'=> "BCFD - MDT",
                    'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
                    'embeds'=>[
                        [
                            'title'=>'Fin de service de ' . $user->name,
                            'description'=> 'temps de service : ' . $formated[0]. ' h et ' . $formated[1] . ' min(s)',
                            'color'=>'15158332',
                        ]
                    ]
                ]);
            }
            return true;

        }else{
            $user->service= true;
            $service = new Service();
            $service->user_id = $user->id;
            $service->started_at = date('Y-m-d H:i:s',time());
            $service->save();
            $user->save();
            Auth::user()->service = true;
            if($admin){
                Http::post(env('WEBHOOK_SERVICE'),[
                    'username'=> "BCFD - MDT",
                    'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
                    'embeds'=>[
                        [
                            'title'=>'Prise de service de ' . $user->name,
                            'color'=>'3066993',
                            'footer'=>[
                                'text'=>'Mis en service par : ' . Auth::user()->name
                            ]
                        ]
                    ]
                ]);
            }else{
                Http::post(env('WEBHOOK_SERVICE'),[
                    'username'=> "BCFD - MDT",
                    'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
                    'embeds'=>[
                        [
                            'title'=>'Prise de service de ' . $user->name,
                            'color'=>'3066993',
                        ]
                    ]
                ]);
            }
            return true;
        }
    }

    public static function addTime(string $base, string $toadd): string
    {
        $base = explode(':', $base);
        $base['h'] = (int) $base[0];
        $base['m'] = (int) $base[1];
        $base['s'] = (int)$base[2];
        $toadd = explode(':', $toadd);
        $toadd['h'] = (int) $toadd[0];
        $toadd['m'] = (int) $toadd[1];
        $toadd['s'] = (int) $toadd[2];


        $sec = (int) $base["s"] + (int) $toadd['s'];
        if($sec > 59){
            while ($sec > 59){
                $base['m']++;
                $sec = $sec - 60;
            }
            $toadd["s"] = $sec;
        }else{
            $toadd["s"] = $sec;
        }
        $min = (int) $base["m"] + (int) $toadd['m'];
        if($min > 59){
            while ($min > 59){
                $base['h']++;
                $min= $min - 60;
            }
            $toadd['m'] = $min;
        }else{
            $toadd['m'] = $min;
        }
        $toadd['h'] = (int) $base['h'] + (int) $toadd['h'];

        return $toadd['h'] . ':' . $toadd['m'] . ':'. $toadd['s'];
    }

    public static function removeTime(string $base, string $toremove): string
    {
        $base = explode(':', $base);
        $base['h'] = (int) $base[0];
        $base['m'] = (int) $base[1];
        $base['s'] = (int)$base[2];
        $toremove = explode(':', $toremove);
        $toremove['h'] = (int) $toremove[0];
        $toremove['m'] = (int) $toremove[1];
        $toremove['s'] = (int) $toremove[2];
        $baseSec = $base['h']*3600 + $base['m']*60 + $base['s'];
        $toremoveSec = $toremove['h']*3600 + $toremove['m'] *60 + $toremove['s'];
        $final = $baseSec - $toremoveSec;
        $first = $final / 3600;
        $firstR = $final % 3600;
        $seconde = $firstR / 60;
        $secondeR = $firstR % 60;

        $first = (string) ($first < 10 ? '0'. (int) $first :  (int)$first);
        $seconde = (string) ($seconde < 10 ? '0'. (int) $seconde :  (int) $seconde);
        $secondeR = (string) ($secondeR < 10 ? '0'. (int) $secondeR :  (int)$secondeR);

        $first = str_replace('0-','',$first);
        $seconde = str_replace('0-','',$seconde);
        $secondeR = str_replace('0-','',$secondeR);


        return $first . ':' .  $seconde . ':'. $secondeR;
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

    public function postModifyTimeServiceRequest(request $request)
    {
        //create request
    }

    public function acceptModifyTimeServiceRequest(string $id)
    {
        //accept request
    }

    public function refuseModifyTimeServiceRequest(string $id)
    {
        //refuse request
    }

    public function getAllwaitingModifyTimeServiceRequest()
    {
        //Get all waiting request
    }

    public function getMyModifyTimeServiceRequest()
    {
        //Get my requests
    }

}
