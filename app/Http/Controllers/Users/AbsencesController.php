<?php

namespace App\Http\Controllers\Users;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\Service\ServiceGetterController;
use App\Models\AbsencesList;
use App\Models\WeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AbsencesController extends Controller
{

    public function getMyAbsences(){
        $this->authorize('viewMy', AbsencesList::class);

        return response()->json([
            'status'=>'OK',
            'abs'=>AbsencesList::where('user_id', \Auth::user()->id)->get()->take(20),
        ]);
    }

    public function postMyReqAbsence(Request $request){
        $this->authorize('create', AbsencesList::class);

        $request->validate([
            'reason'=>['string','required'],
            'start_at'=>['date', 'required','after_or_equal:'.date('Y-m-d', time())],
            'end_at'=>['date', 'required','after_or_equal:'.date('Y-m-d', strtotime($request->start_at . ' + 5 days'))],
        ]);

        $abs = new AbsencesList();
        $abs->end_at = $request->end_at;
        $abs->start_at = $request->start_at;
        $abs->reason = $request->reason;
        $abs->user_id = \Auth::user()->id;
        $abs->save();
        $embed = [
            [
                'title'=>'Demande d\'absence :',
                'color'=>'1285790',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$abs->GetUser->name,
                        'inline'=>false
                    ],[
                        'name'=>'Dates : ',
                        'value'=>'Du ' . date('d/m/Y', strtotime($abs->start_at)) . ' au ' . date('d/m/Y', strtotime($abs->end_at)),
                        'inline'=>false
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$abs->reason,
                        'inline'=>false
                    ]
                ],
            ]
        ];

        \Discord::postMessage(DiscordChannel::Absences, $embed, $abs);

        $logs = new LogsController();
        $logs->DemandesLogging('creating', 'absencess', $abs->id, Auth::user()->id);



        Notify::broadcast('Demande ajoutée',1, Auth::user()->id);
        return response()->json([],201);


    }

    public function getAbsences(){

        \Gate::authorize('viewAny', AbsencesList::class);
        $abs = AbsencesList::where('accepted', null);
        if($abs->count() < 10){
            $abs = AbsencesList::all()->take(15);
        }else{
            $abs = $abs->get();
        }

        foreach ($abs as $ab){
            $ab->GetUser;
            if(!is_null($ab->admin_id)){
                $ab->GetAdmin;
            }
        }


        return response()->json([
            'status'=>'OK',
            'abs'=>$abs,
        ]);
    }

    public function acceptReqAbsence(Request $request, string $id){
        $this->authorize('update', AbsencesList::class);
        $abs = AbsencesList::where('id', $id)->first();
        if(!isset($abs)){
            Notify::broadcast('l\'absence a été supprimée',4, Auth::user()->id);
            return response()->json([],201);
        }
        $abs->accepted = true;
        $abs->admin_id = Auth::user()->id;

        //Creating Date var
        $from = date_create($abs->start_at);
        $to = date_create($abs->end_at);
        $interval = date_diff($from, $to);
        $NumberOfDay = (int) $interval->format('%a') +1;

        //Get Start day
        $day = $abs->start_at;
        //Get user
        $user = $abs->GetUser;

        //User Service
        $fire = false;
        if($user->fire || ($user->medic && $user->crossService)) $fire = true;
        $medic = false;
        if($user->medic || ($user->fire && $user->crossService)) $medic = true;
        $medicName = 'SAMS';
        $fireName = 'LSCoFD';

        for ($a = 1; $a <= $NumberOfDay; $a++){
            $dayTime = strtotime($day);
            $dayName = LayoutController::getdaystring($dayTime);

            $weekNumber = ServiceGetterController::getWeekNumber($dayTime);
            if($fire){
                self::updateWeekService($fireName, $user->id, $dayName, $weekNumber, 'absent(e)');
            }
            if ($medic){
                self::updateWeekService($medicName, $user->id, $dayName, $weekNumber, 'absent(e)');
            }
            $day = date('Y-m-d', strtotime($day . ' + 1 day'));
        }


        $embed = [
            [
                'title'=>'Demande d\'absence (acceptée) : ',
                'color'=>'65361',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$abs->GetUser->name,
                        'inline'=>false
                    ],[
                        'name'=>'Dates : ',
                        'value'=>'Du ' . date('d/m/Y', strtotime($abs->start_at)) . ' au ' . date('d/m/Y', strtotime($abs->end_at)),
                        'inline'=>false
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$abs->reason,
                        'inline'=>false
                    ]
                ],
                'footer'=>[
                    'text' => 'Acceptée par : ' . Auth::user()->name,
                ]
            ]
        ];

        $logs = new LogsController();
        $logs->DemandesLogging('accept req of user n°'.$abs->user_id, 'absence', $abs->id, Auth::user()->id);

        if($abs->discord_msg_id){
            \Discord::updateMessage(DiscordChannel::Absences, $abs->discord_msg_id, $embed);
        }else{
            \Discord::postMessage(DiscordChannel::Absences, $embed, $abs);
        }

        $abs->save();

        Notify::broadcast('Absence aceptée ',1, Auth::user()->id);
        return response()->json([],201);

    }

    private static function updateWeekService(string $service,int $userID, string $day, int $weekNumber, string $replace){
        $weekService = WeekService::where('user_id', $userID)->where('week_number', $weekNumber)->where('service', $service);
        if($weekService->count() === 1){
            $weekService = $weekService->first();
        }else{
            $weekService = new WeekService();
            $weekService->user_id = $userID;
            $weekService->week_number = $weekNumber;
            $weekService->service = $service;
        }
        $weekService[$day] = $replace;
        $weekService->save();
    }

    public function refuseReqAbsence(Request $request, string $id){
        $this->authorize('update', AbsencesList::class);
        $abs = AbsencesList::where('id', $id)->first();
        if(!isset($abs)){
            Notify::broadcast('l\'absence a été supprimée',4, Auth::user()->id);
            return response()->json([],201);
        }
        $abs->accepted = false;
        $abs->admin_id = Auth::user()->id;
        $abs->save();

        $embed = [
            [
                'title'=>'Demande d\'absence (refusée) : ',
                'color'=>'16711684',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$abs->GetUser->name,
                        'inline'=>false
                    ],[
                        'name'=>'Dates : ',
                        'value'=>'Du ' . date('d/m/Y', strtotime($abs->start_at)) . ' au ' . date('d/m/Y', strtotime($abs->end_at)),
                        'inline'=>false
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$abs->reason,
                        'inline'=>false
                    ]
                ],
                'footer'=>[
                    'text' => 'Refusée par : ' . Auth::user()->name,
                ]
            ]
        ];

        $logs = new LogsController();
        $logs->DemandesLogging('refuse req of user n°'.$abs->user_id, 'absence', $abs->id, Auth::user()->id);

        if($abs->discord_msg_id){
            \Discord::updateMessage(DiscordChannel::Absences, $abs->discord_msg_id, $embed);
        }else{
            \Discord::postMessage(DiscordChannel::Absences, $embed, $abs);
        }

        Notify::broadcast('Absence refusée',1, Auth::user()->id);
        return response()->json([],201);
    }

    public function deleteAbsence(Request $request, string $id){
        $this->authorize('viewMy', AbsencesList::class);
        $abs = AbsencesList::where("id", $id)->first();
        if ($abs->accepted){
            //Creating Date var
            $from = date_create($abs->start_at);
            $to = date_create($abs->end_at);
            $interval = date_diff($from, $to);
            $NumberOfDay = (int) $interval->format('%a') +1;

            //Get Start day
            $day = $abs->start_at;
            //Get user
            $user = $abs->GetUser;

            //User Service
            $fire = false;
            if($user->fire || ($user->medic && $user->crossService)) $fire = true;
            $medic = false;
            if($user->medic || ($user->fire && $user->crossService)) $medic = true;
            $medicName = 'SAMS';
            $fireName = 'LSCoFD';

            for ($a = 1; $a <= $NumberOfDay; $a++){
                $dayTime = strtotime($day);
                $dayName = LayoutController::getdaystring($dayTime);

                $weekNumber = ServiceGetterController::getWeekNumber($dayTime);
                if($fire){
                    self::updateWeekService($fireName, $user->id, $dayName, $weekNumber, '00:00:00');
                }
                if ($medic){
                    self::updateWeekService($medicName, $user->id, $dayName, $weekNumber, '00:00:00');
                }
                $day = date('Y-m-d', strtotime($day . ' + 1 day'));
            }

        }

        Notify::broadcast('Absence supprimé',1, Auth::user()->id);
        $embed = [
            [
                'title'=>'Absence annulée : ',
                'color'=>'16539139',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$abs->GetUser->name,
                        'inline'=>false
                    ],[
                        'name'=>'Dates : ',
                        'value'=>'Du ' . date('d/m/Y', strtotime($abs->start_at)) . ' au ' . date('d/m/Y', strtotime($abs->end_at)),
                        'inline'=>false
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$abs->reason,
                        'inline'=>false
                    ]
                ],
                'footer'=>[
                    'text' => 'Acceptée par : ' . Auth::user()->name,
                ]
            ]
        ];

        $logs = new LogsController();
        $logs->DemandesLogging('delete req of user n°'.$abs->user_id, 'absence', $abs->id, Auth::user()->id);
        $abs->delete();

        if($abs->discord_msg_id){
            \Discord::updateMessage(DiscordChannel::Absences, $abs->discord_msg_id, $embed);
        }else{
            \Discord::postMessage(DiscordChannel::Absences, $embed, $abs);
        }
        return response()->json([],201);

    }



}
