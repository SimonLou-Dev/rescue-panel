<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Http\Controllers\Service\OperatorController;
use App\Http\Controllers\Service\ServiceGetterController;
use App\Models\ObjRemboursement;
use App\Models\RemboursementList;
use App\Models\WeekRemboursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RemboursementsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }


    public function getRemboursementOfUser(): \Illuminate\Http\JsonResponse
    {
        $remboursements = RemboursementList::where('user_id', Auth::user()->id)->where('week_number', ServiceGetterController::getWeekNumber())->orderByDesc('id')->get();
        $obs = ObjRemboursement::all();
        foreach ($remboursements as $remboursement){
            $remboursement->getItem;
        }
        return response()->json(['status'=>'OK', 'remboursements'=>$remboursements, 'obj'=>$obs]);
    }

    public function addRemboursement(Request $request): \Illuminate\Http\JsonResponse
    {
        $item = (int) $request->item;
        $item = ObjRemboursement::where('id', $item)->first();
        $userRemboursements = WeekRemboursement::where('week_number', ServiceGetterController::getWeekNumber())->where('user_id', Auth::user()->id)->first();
        if(!isset($userRemboursements)){
            $userRemboursements = new WeekRemboursement();
            $userRemboursements->week_number = ServiceGetterController::getWeekNumber();
            $userRemboursements->user_id = Auth::user()->id;
            $userRemboursements->total = $item->price;
        }else{
            $userRemboursements->total = (int) $userRemboursements->total + (int) $item->price;
        }
        $rmbsem = new RemboursementList();
        $rmbsem->user_id = Auth::user()->id;
        $rmbsem->item_id = $item->id;
        $rmbsem->total = $item->price;
        $rmbsem->week_number = ServiceGetterController::getWeekNumber();
        $rmbsem->save();
        $userRemboursements->save();
        Http::post(env('WEBHOOK_REMBOURSEMENTS'), [
            'username'=> "BCFD - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                [
                    'title'=>'Ajout d\'un remboursement :',
                    'color'=>'16745560 ',
                    'fields'=>[
                        [
                            'name'=>'Item : ',
                            'value'=>$item->name . '($' . $item->price . ')',
                            'inline'=>true
                        ]
                    ],
                    'footer'=>[
                        'text' => 'Membre : ' . Auth::user()->name
                    ]
                ]
            ]
        ]);


        event(new Notify('Remboursement pris en compte',1));
        return response()->json(['stauts'=>'OK'],201);
    }

    public function deleteRemboursement(string $itemid): \Illuminate\Http\JsonResponse
    {
        $itemid = (int) $itemid;
        $item = RemboursementList::where('id', $itemid)->first();
        $userRemboursement = WeekRemboursement::where('user_id', Auth::user()->id)->where('week_number', ServiceGetterController::getWeekNumber())->first();
        $userRemboursement->total = (int) $userRemboursement->total -  (int) $item->getItem->price;
        $userRemboursement->save();
        Http::post(env('WEBHOOK_REMBOURSEMENTS'), [
            'username'=> "BCFD - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                [
                    'title'=>'Suppression d\'un remboursement :',
                    'color'=>'16745560 ',
                    'fields'=>[
                        [
                            'name'=>'Item : ',
                            'value'=>$item->name . '($' . $item->price . ')',
                            'inline'=>true
                        ]
                    ],
                    'footer'=>[
                        'text' => 'Membre : ' . Auth::user()->name
                    ]
                ]
            ]
        ]);

        $item->delete();
        event(new Notify('Remboursement supprimé',1));
        return response()->json(['status'=>'OK']);
    }

    public function getRemboursementByWeek(string $weeknumber = null): \Illuminate\Http\JsonResponse
    {
        if($weeknumber == '' || $weeknumber == null){
            $weeknumber = ServiceGetterController::getWeekNumber();
        }else{
            $weeknumber = (int) $weeknumber;
        }
        $list = WeekRemboursement::orderByDesc('id')->where('week_number', $weeknumber)->get();
        foreach ($list as $item){
            $item->GetUser;
        }
        return response()->json([
            'status'=>'OK',
            'list'=>$list,
            'maxweek'=>ServiceGetterController::getWeekNumber(),
        ]);
    }


}
