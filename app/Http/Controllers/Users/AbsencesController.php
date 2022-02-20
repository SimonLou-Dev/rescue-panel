<?php

namespace App\Http\Controllers\Users;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\AbsencesList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AbsencesController extends Controller
{

    public function getMyAbsences(){
        return response()->json([
            'status'=>'OK',
            'abs'=>AbsencesList::where('user_id', \Auth::user()->id)->get()->take(20),
        ]);
    }

    public function postMyReqAbsence(Request $request){
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



        Notify::broadcast('Demande ajoutée',1, Auth::user()->id);
        return response()->json([],201);


    }


}
