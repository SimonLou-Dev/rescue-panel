<?php

namespace App\Http\Controllers;

use App\Models\DayService;
use App\Models\Services;
use DateInterval;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ServiceController extends Controller
{
    public function getUserService(): \Illuminate\Http\JsonResponse
    {
        $date = (int) date('W', time());
        if($date == 1 ){
            $week = DayService::where('week', '=', 1)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else if($date == 2){
            $week = DayService::where('week', '<=', 2)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else if($date == 3){
            $week = DayService::where('week', '<=', 3)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else{
            $date = $date -3;
            $week = DayService::where('week', '>', $date)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }
        $userserivce= Services::where('UserId', Auth::user()->id)->orderBy('id','desc')->take(20)->get();
        return response()->json([
            'status'=>'ok',
            'week'=>$week,
            'services'=>$userserivce,
        ]);

    }
    public function getAllservice($semaine = NULL): \Illuminate\Http\JsonResponse
    {
        $max = (int) date('W', time());
        if($semaine){
            $date= (int) $semaine;
        }else{
            $date = (int) date('W', time());
        }
        $service = DayService::where('week', $date)->get();
        $a= 0;
        while($a < count($service)){
            $service[$a]->user;
            $a++;
        }
        return response()->json([
            'service'=>$service,
            'maxweek'=>$max,
        ]);
    }
    public function setServiceByAdmin(Request $request, $userid){
        $user = User::where('id', $userid)->first();
        $this->setService($user, true);

        return response()->json(['status'=>'OK']);
    }
    public function addRows(){
        $week =  date('W', time());
        $users = \App\Models\User::where('grade', '>', 1)->get();
        $dayservice = DayService::where('week', $week)->get('user_id');
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
        DayService::insert($datas);
        return response()->json(['status'=>"OK"],201);
    }
    public static function setService($user, bool $admin){
        if($user->OnService){
            $user->OnService = false;
            $user->save();
            $service = Services::where('UserId', $user->id)->whereNull('Total')->first();
            $start = date_create($service->Started_at);
            $interval = $start->diff(date_create(date('Y-m-d H:i:s', time())));
            $diff = $interval->d*24 + $interval->h;
            $formated = $diff . ':' . $interval->format('%I:%S');
            $week =  date('W', time());
            $service->EndedAt = date('H:i:s', time());
            $service->Total = $formated;
            $service->save();

            $dayservice = DayService::where('week', $week)->where('user_id', $user->id);
            if($dayservice->count() == 1){
                $dayservice = $dayservice->first();
                $total = $dayservice->total;
                $day = $dayservice[LayoutController::getdaystring()];
                $service = new ServiceController();
                $dayservice->total = $service->addTime($total, $interval);
                $dayservice[LayoutController::getdaystring()] = $service->addTime($day, $interval);
                $dayservice->save();

            }else{
                $dayservice = new DayService();
                $dayservice->user_id = $user->id;
                $dayservice['week'] = $week;
                $dayservice[LayoutController::getdaystring()] = $formated;
                $dayservice->total = $formated;
                $dayservice->save();
            }
            $user->save();
            if($admin){
                Http::post(env('WEBHOOK_SERVICE'),[
                    'embeds'=>[
                        [
                            'title'=>'Fin de service de ' . $user->name,
                            'description'=> 'temps de service : ' . $formated,
                            'color'=>'15158332',
                            'footer'=>[
                                'text'=>'stoppé par : ' . Auth::user()->name
                            ]
                        ]
                    ]
                ]);
            }else{
                Http::post(env('WEBHOOK_SERVICE'),[
                    'embeds'=>[
                        [
                            'title'=>'Fin de service de ' . $user->name,
                            'description'=> 'temps de service : ' . $formated,
                            'color'=>'15158332',
                        ]
                    ]
                ]);
            }

        }else{
            $user->OnService= true;
            $service = new Services();
            $service->UserId = $user->id;
            $service->Started_at = date('Y-m-d H:i:s',time());
            $service->save();
            $user->save();
            Auth::user()->Onservice = true;
            if($admin){
                Http::post(env('WEBHOOK_SERVICE'),[
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
                    'embeds'=>[
                        [
                            'title'=>'Prise de service de ' . $user->name,
                            'color'=>'3066993',
                        ]
                    ]
                ]);
            }
        }
    }

    private function addTime(string $base, DateInterval  $toadd): string
    {
        $base = explode(':', $base);
        $base['h'] = (int) $base[0];
        $base['m'] = (int) $base[1];
        $base['s'] = (int)$base[2];
        $sec = $base["s"] + $toadd->s;
        if($sec >= 60){
            while ($sec >= 60){
                $base['m']++;
                $sec = $sec - 60;
            }
            $toadd->s = $sec;
        }else{
            $toadd->s = $sec;
        }
        $min = $base["m"] + $toadd->i;
        if($min >= 60){
            while ($min >= 60){
                $base['h']++;
                $min= $min - 60;
            }
            $toadd->i = $min;
        }else{
            $toadd->i = $min;
        }
        $toadd->h = $base['h'] + $toadd->h;

        return $toadd->h . ':' . $toadd->i . ':'. $toadd->s;
    }

}
