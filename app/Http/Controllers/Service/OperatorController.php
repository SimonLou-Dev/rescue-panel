<?php

namespace App\Http\Controllers\Service;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\ServiceController;
use App\Models\Service;
use App\Models\User;
use App\Models\WeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public static function setService(User $user, bool $admin): bool
    {
        if ($user->last_service_update != null && !$admin){
            if($user->last_service_update + 120 > now()->timestamp) {
                event(new Notify('Stop SPAMMMMMMM !!!!! ;)', 4));
                return true;
            }
        }

        if(!$admin){
            $user->last_service_update = now()->timestamp;
        }


        if($user->service){
            $user->service = false;
            $user->serviceState = null;
            $user->save();
            $service = Service::where('user_id', $user->id)->whereNull('Total')->first();
            $start = date_create($service->started_at);
            $interval = $start->diff(date_create(date('Y-m-d H:i:s', time())));
            $diff = $interval->d*24 + $interval->h;
            $formated = $diff . ':' . $interval->format('%i:%S');
            $week =  ServiceGetterController::getWeekNumber();
            $service->ended_at = date('H:I:s', time());
            $service->total = $formated;

            $service->save();

            $WeekService = WeekService::where('week_number', $week)->where('user_id', $user->id);
            if($WeekService->count() == 1){
                $WeekService = $WeekService->first();
                $total = $WeekService->total;
                $day = $WeekService[LayoutController::getdaystring()];
                $service = new OperatorController();
                $WeekService->total = $service::addTime($formated, $total);
                $WeekService[LayoutController::getdaystring()] = $service::addTime($formated, $day);
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
}
