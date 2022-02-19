<?php

namespace App\Http\Controllers\Service;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\ServiceController;
use App\Jobs\ProcessEmbedPosting;
use App\Models\LogServiceState;
use App\Models\Service;
use App\Models\User;
use App\Models\WeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use TimeCalculate;

class OperatorController extends Controller
{

    public static function setService(User $user, bool $admin): bool
    {

        if($user->OnService){
            $user->OnService = false;
            $user->save();

            $service = Service::where('user_id', $user->id)->whereNull('total')->first();
            $start = date_create($service->started_at);
            $interval = $start->diff(date_create(date('Y-m-d H:i:s', time())));
            $diff = $interval->d*24 + $interval->h;
            $formated = ($diff < 10 ? '0'.$diff : $diff) . ':' . $interval->format('%I:%S');
            $week =  ServiceGetterController::getWeekNumber();
            $service->ended_at = date('H:i:s', time());
            $service->service = $user->service;
            $service->total = $formated;
            $service->save();

            $WeekService = WeekService::where('week_number', $week)->where('user_id', $user->id);
            if($WeekService->count() == 1){
                $WeekService = $WeekService->first();
                $total = $WeekService->total;
                $day = $WeekService[LayoutController::getdaystring()];
                $WeekService->total = TimeCalculate::HoursAdd($total, $formated);
                $WeekService[LayoutController::getdaystring()] = TimeCalculate::HoursAdd($day, $formated);
                $WeekService->save();

            }else{
                $WeekService = new WeekService();
                $WeekService->user_id = $user->id;
                $WeekService->service = $user->service;
                $WeekService['week_number'] = $week;
                $WeekService[LayoutController::getdaystring()] = $formated;
                $WeekService->total = $formated;
                $WeekService->save();
            }
        }else{
            $user->OnService= true;
            $service = new Service();
            $service->user_id = $user->id;
            $service->started_at = date('Y-m-d H:i:s',time());
            $service->service = $user->service;
            $service->save();
            $user->save();
        }

        //Il faut reverse les état de service
        $text = '';
        $embed = array();
        if($user->OnService){
            $text = 'Vous êtes en service !';
            $embed = [
                [
                    'title'=>'Prise de service de ' . $user->name,
                    'color'=>'709104',
                    'footer'=>[
                        'text'=>($admin ? 'Mis en service par : ' . Auth::user()->name : '')
                    ]
                ]
            ];
        }else{
            $text = 'Vous n\'êtes plus en service';
            $embed = [
                [
                    'title'=>'Fin de service de ' . $user->name,
                    'color'=>'20991',
                    'fields'=>[
                        [
                            'name'=>'Duréee : ',
                            'value'=>$formated,
                            'inline'=>true
                        ]
                    ],
                    'footer'=>[
                        'text'=>($admin ? 'Mis en service par : ' . Auth::user()->name : '')
                    ]
                ]
            ];
        }

        UserUpdated::broadcast($user);

        Notify::dispatch($text, 1, $user->id);
        if($user->OnService){
            \Discord::postMessage(DiscordChannel::Service, $embed, $service);
        }else{
            if(is_null($service->discord_msg_id)){
                \Discord::postMessage(DiscordChannel::Service, $embed, $service);
            }else{
                \Discord::updateMessage(DiscordChannel::Service, $service->discord_msg_id, $embed);
            }

        }


        return  true;
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

        return ($toadd['h']< 10 ? '0'. $toadd['h'] : $toadd['h']) . ':' . ($toadd['m']< 10 ? '0'. $toadd['m'] : $toadd['m']) . ':'. ($toadd['s']< 10 ? '0'. $toadd['s'] : $toadd['s']);
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

        $first = (int) str_replace('0-','',$first);
        $seconde = (int) str_replace('0-','',$seconde);
        $secondeR = (int) str_replace('0-','',$secondeR);

        return ($first < 10 ? '0' . $first : $first). ':' .  ($seconde < 10 ? '0' . $seconde : $seconde) . ':'. ($secondeR < 10 ? '0' . $secondeR : $secondeR);
    }

    public static function secondToTimeConvert(string $time): string
    {
        $mins = floor($time / 60);
        $hours = floor($time / 3600);
        $mins = $mins - $hours*60;
        return ($hours < 10 ? '0'.$hours : $hours) . ':' . ($mins < 10 ? '0'.$mins : $mins);
}

    // $action = 1 add  else remove
    public static function ajustementCalculator(string $base, string $modifier, int $action){
        $final = "";
        Log::critical($base. ' | ' . $modifier . ' | ' . $action);

        if($action === 1){
            if($base == '00:00:00'){
                $final = '+'.$modifier;
            }else{
                $symbole = substr($base, 0, 1-(strlen($base)));
                if($symbole == '+'){
                    $final = '+'.OperatorController::addTime(substr($base,1), $modifier);
                }else{
                    $calculate = OperatorController::removeTime(substr($base,1), $modifier);
                    $calculate = str_replace(['+','-'], '', $calculate);
                    $base =  str_replace(['+','-'], '', $base);
                    Log::critical($calculate . ' | ' .  $base);
                    $ajustement =  explode(':', $calculate);
                    $base = explode(':',  $base);
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

                    $final = $operator.$calculate;

                }
            }
        }else{


            if($base == '00:00:00'){
                $base = '-'.$modifier;
            }else{
                $ajustement = $base;
                $symbole = substr($ajustement, 0, 1-(strlen($ajustement)) );
                if($symbole == '-'){
                    $final = '-'.OperatorController::addTime(substr($ajustement,1), $modifier);
                }else{
                    $calculate =  OperatorController::removeTime(substr($ajustement,1), $modifier);
                    $calculate = str_replace(['+','-'], '', $calculate);
                    $ajustement = explode(':', $calculate);
                    $base = explode(':',  $base);
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

                    $final = $operator.$calculate;
                }
            }


        }
        return $final;
    }
}
