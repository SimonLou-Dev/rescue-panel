<?php

namespace App\Http\Controllers\Service;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\ModifyServiceReq;
use App\Models\User;
use App\Models\WeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ModifierReqController extends Controller
{


    public function postModifyTimeServiceRequest(request $request)
    {
        $request->validate([
            'reason'=>'required',
            'action'=>'digits_between:1,2',
            'time_quantity'=>'regex:/[0-60]+:+[0-59]/'
        ]);
        $req = new ModifyServiceReq();
        $req->user_id = Auth::user()->id;
        $req->week_number = ServiceGetterController::getWeekNumber();
        $req->reason = $request->reason;
        $req->service = Session::get('service')[0];
        if($request->action == 2){
            $req->adder = 0;
        }
        if($request->action == 1){
            $req->adder = 1;
        }
        $req->time_quantity = $request->time_quantity;
        $req->save();
        Notify::broadcast('Votre demande a été enregistrée',1,Auth::user()->id);
        return response()->json(['status'=>'OK'],201);


    }

    public function acceptModifyTimeServiceRequest(string $id)
    {
        $reqst = ModifyServiceReq::where('id',$id)->first();
        $reqst->acceped = true;
        $reqst->admin_id = Auth::user()->id;
        $user = $reqst->GetUser;
        $week = WeekService::where('user_id',$user->id)->where('week_number',$reqst->week_number);
        $time = OperatorController::secondToTimeConvert($reqst->time_quantity).':00';


        if($week->count() == 1){
            $week = $week->first();
            $ajustement = OperatorController::ajustementCalculator($week->ajustement, $time, $reqst->adder);
            if($reqst->adder){
                $total = OperatorController::addTime($week->total, $time);
            }else{
                $total = OperatorController::removeTime($week->total, $time);
            }
            $week->total = $total;
            $week->ajustement = $ajustement;

        }else{
            $week = new WeekService();
            $week->week_number = $reqst->week_number;
            $week->user_id=$user->id;
            $week->total = $time;
            $week->ajustement = $time;
        }
        $week->save();
        $reqst->save();
        event(new Notify('Le temps a été modifié',1));
        return response()->json([]);
    }

    public function refuseModifyTimeServiceRequest(string $id)
    {

        $reqst = ModifyServiceReq::where('id',$id)->first();
        $reqst->acceped = false;
        $reqst->admin_id = Auth::user()->id;
        $reqst->save();
        event(new Notify('La demande a été réfusée',1));
        return response()->json([]);
    }

    public function getAllModifyTimeServiceRequest(): \Illuminate\Http\JsonResponse
    {
        $reqs = ModifyServiceReq::where('acceped', null)->orderBy('id','desc')->take(100)->get();
        if(count($reqs) < 20){
            $reqs = ModifyServiceReq::take(100)->orderBy('id','desc')->get();
        }

        foreach ($reqs as $req){
            if ($req->admin_id){
                $req->GetAdmin;
            }
            $req->GetUser;
            $req->time_quantity = OperatorController::secondToTimeConvert($req->time_quantity);
        }


        return response()->json([
            'list'=>$reqs
        ]);
    }

    public function getMyModifyTimeServiceRequest(): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::id())->first();

        $reqs = ModifyServiceReq::where('user_id', $user->id)->where('service',Session::get('service')[0])->orderBy('id','desc')->take(10)->get();

        return response()->json([
            'status'=>'OK',
            'reqs'=>$reqs
        ]);

    }
}
