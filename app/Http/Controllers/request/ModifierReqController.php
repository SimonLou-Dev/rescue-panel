<?php

namespace App\Http\Controllers\request;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\Service\ServiceGetterController;
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
        $this->authorize('create', ModifyServiceReq::class);

        $request->validate([
            'reason'=>'required',
            'action'=>['int','digits_between:1,2'],
            'time_quantity'=>'required'
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

        $logs = new LogsController();
        $logs->DemandesLogging('created', 'service', $req->id, $req->user_id);

        Notify::broadcast('Votre demande a été enregistrée',1,Auth::user()->id);
        return response()->json(['status'=>'OK'],201);


    }

    public function acceptModifyTimeServiceRequest(string $id)
    {
        $this->authorize('update', ModifyServiceReq::class);
        $reqst = ModifyServiceReq::where('id',$id)->first();
        $reqst->accepted = true;
        $reqst->admin_id = Auth::user()->id;
        $user = $reqst->GetUser;
        $week = WeekService::where('user_id',$user->id)->where('week_number',$reqst->week_number)->where('service', Session::get('service')[0]);
        $time = ($reqst->adder ? '':'-') . $reqst->time_quantity.':00';


        if($week->count() == 1){
            $week = $week->first();
            $ajustement = \TimeCalculate::HoursAdd($week->ajustement, $time);
            $total = \TimeCalculate::HoursAdd($week->total,$time);
            $week->total = $total;
            $week->ajustement = $ajustement;

        }else{
            $week = new WeekService();
            $week->week_number = $reqst->week_number;
            $week->user_id=$user->id;
            $week->total = $time;
            $week->ajustement = $time;
            $week->service = Session::get('service')[0];
        }
        $week->save();
        $reqst->save();
        $logs = new LogsController();
        $logs->DemandesLogging('accept req of user n°'.$reqst->user_id, 'service', $reqst->id, Auth::user()->id);
        Notify::broadcast('Le temps a été modifié',1, Auth::user()->id);
        return response()->json([]);
    }

    public function refuseModifyTimeServiceRequest(string $id)
    {
        $this->authorize('update', ModifyServiceReq::class);
        $reqst = ModifyServiceReq::where('id',$id)->first();
        $reqst->accepted = false;
        $reqst->admin_id = Auth::user()->id;
        $reqst->save();
        $logs = new LogsController();
        $logs->DemandesLogging('refuse req of user n°'.$reqst->user_id, 'service', $reqst->id, Auth::user()->id);
        Notify::broadcast('La demande a été réfusée',1, Auth::user()->id);
        return response()->json([]);
    }

    public function getAllModifyTimeServiceRequest(): \Illuminate\Http\JsonResponse
    {
        $this->authorize('viewAny', ModifyServiceReq::class);
        $reqs = ModifyServiceReq::where('accepted', null)->where('service', Session::get('service')[0]);
        if($reqs->count() == 0){
            $reqs = ModifyServiceReq::where('service', Session::get('service')[0])->get()->take(15);
        }else{
            $reqs = $reqs->get();
        }

        foreach ($reqs as $req){
            if ($req->admin_id){
                $req->GetAdmin;
            }
            $req->GetUser;
        }


        return response()->json([
            'list'=>$reqs
        ]);
    }

    public function getMyModifyTimeServiceRequest(): \Illuminate\Http\JsonResponse
    {
        $this->authorize('viewMy', ModifyServiceReq::class);
        $user = User::where('id', Auth::id())->first();

        $reqs = ModifyServiceReq::where('user_id', $user->id)->where('service',Session::get('service')[0])->orderBy('id','desc')->take(10)->get();

        return response()->json([
            'status'=>'OK',
            'reqs'=>$reqs
        ]);

    }
}
