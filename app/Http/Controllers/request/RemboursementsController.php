<?php

namespace App\Http\Controllers\request;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Service\OperatorController;
use App\Http\Controllers\Service\ServiceGetterController;
use App\Jobs\ProcessEmbedPosting;
use App\Models\ObjRemboursement;
use App\Models\RemboursementList;
use App\Models\WeekRemboursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RemboursementsController extends Controller
{

    public function getRemboursementOfUser(): \Illuminate\Http\JsonResponse
    {
        $remboursements = RemboursementList::where('service', Session::get('service')[0])->where('user_id', Auth::user()->id)->orderByDesc('id')->take(10)->get();
        return response()->json(['status'=>'OK', 'remboursements'=>$remboursements]);
    }

    public function addRemboursement(Request $request): \Illuminate\Http\JsonResponse
    {

        $rmbsem = new RemboursementList();
        $rmbsem->user_id = Auth::user()->id;
        $rmbsem->reason = $request->reason;
        $rmbsem->montant = $request->montant;
        $rmbsem->week_number = ServiceGetterController::getWeekNumber();
        $rmbsem->service = Session::get('service')[0];
        $rmbsem->save();
        $embed = [
            [
                'title'=>'Demande de remboursement :',
                'color'=>'16745560',
                'fields'=>[
                    [
                        'name'=>'Raison : ',
                        'value'=>$rmbsem->reason . ' ($' . $rmbsem->montant . ')',
                        'inline'=>true
                    ]
                ],
                'footer'=>[
                    'text' => 'Membre : ' . Auth::user()->name . ' (' . Session::get('service')[0] . ')'
                ]
            ]
        ];

        if(Session::get('service')[0] === 'SAMS'){
            \Discord::postMessage(DiscordChannel::MedicRemboursement, $embed, $rmbsem);
        }else{
            \Discord::postMessage(DiscordChannel::FireRemboursement, $embed, $rmbsem);
        }


        Notify::broadcast('Demande postée',1, Auth::user()->id);
        return response()->json(['stauts'=>'OK'],201);
    }

    public function acceptRemboursement(Request $request, string $rmbId){
        $rmb= RemboursementList::where('id',$rmbId)->first();
        $rmb->admin_id = Auth::user()->id;
        $rmb->accepted = true;
        $rmb->save();
        $userRemboursements = WeekRemboursement::where('service', $rmb->getUser->service)->where('week_number', ServiceGetterController::getWeekNumber())->where('user_id', $rmb->getUser->id)->first();
        if(!isset($userRemboursements)){
            $userRemboursements = new WeekRemboursement();
            $userRemboursements->week_number = ServiceGetterController::getWeekNumber();
            $userRemboursements->user_id = $rmb->getUser->id;
            $userRemboursements->total = $rmb->montant;
            $userRemboursements->service = Session::get('service')[0];
        }else{
            $userRemboursements->total = (int) $userRemboursements->total + (int) $rmb->montant;
        }
        $userRemboursements->save();

        $embed = [
            [
                'title'=>'Validation d\'une demande de remboursement :',
                'color'=>'65361',
                'fields'=>[
                    [
                        'name'=>'Raison : ',
                        'value'=>$rmb->reason . ' ($' . $rmb->montant . ')',
                        'inline'=>true
                    ],[
                        'name'=>'Membre : ',
                        'value'=>$rmb->getUser->name . ' ('.$rmb->service.')' ,
                        'inline'=>true
                    ]
                ],
                'footer'=>[
                    'text' => 'Validé par : ' . Auth::user()->name . ' (' . Session::get('service')[0] . ')'
                ]
            ]
        ];

        if($rmb->discord_msg_id){
            if(Session::get('service')[0] === 'SAMS'){
                \Discord::updateMessage(DiscordChannel::MedicRemboursement,$rmb->discord_msg_id , $embed);
            }else{
                \Discord::updateMessage(DiscordChannel::FireRemboursement,$rmb->discord_msg_id, $embed);
            }
        }else{
            if(Session::get('service')[0] === 'SAMS'){
                \Discord::postMessage(DiscordChannel::MedicRemboursement, $embed);
            }else{
                \Discord::postMessage(DiscordChannel::FireRemboursement, $embed);
            }
        }

        Notify::broadcast('Remboursement validé',1, Auth::user()->id);
        return response()->json(['stauts'=>'OK'],201);


    }

    public function refuseRemboursement(Request $request, string $rmbId){
        $rmb= RemboursementList::where('id',$rmbId)->first();
        $rmb->admin_id = Auth::user()->id;
        $rmb->accepted = false;
        $rmb->save();

        $embed = [
            [
                'title'=>'Remboursement (refusé) :',
                'color'=>'16711684',
                'fields'=>[
                    [
                        'name'=>'Raison : ',
                        'value'=>$rmb->reason . ' ($' . $rmb->montant . ')',
                        'inline'=>true
                    ],[
                        'name'=>'Membre : ',
                        'value'=>$rmb->getUser->name . ' ('.$rmb->service.')' ,
                        'inline'=>true
                    ]
                ],
                'footer'=>[
                    'text' => 'Refusé par : ' . Auth::user()->name . ' (' . Session::get('service')[0] . ')'
                ]
            ]
        ];

        if($rmb->discord_msg_id){
            if(Session::get('service')[0] === 'SAMS'){
                \Discord::updateMessage(DiscordChannel::MedicRemboursement,$rmb->discord_msg_id , $embed);
            }else{
                \Discord::updateMessage(DiscordChannel::FireRemboursement,$rmb->discord_msg_id, $embed);
            }
        }else{
            if(Session::get('service')[0] === 'SAMS'){
                \Discord::postMessage(DiscordChannel::MedicRemboursement, $embed);
            }else{
                \Discord::postMessage(DiscordChannel::FireRemboursement, $embed);
            }
        }


        Notify::broadcast('Remboursement refusé',1, Auth::user()->id);
        return response()->json(['stauts'=>'OK'],201);


    }

    public function gelAllReqremboursement(){
        //$this->authorize('viewAny', Prime::class);
        $rmbs = RemboursementList::where('accepted', null)->where('service', Session::get('service')[0]);
        if($rmbs->count()  == 0){
            $rmbs = RemboursementList::where('service', Session::get('service')[0])->get()->take(15);
        }else{
            $rmbs = $rmbs->get();
        }

        foreach ($rmbs as $rmb){
            $rmb->getUser;
            if($rmb->admin_id >= 0){

                if($rmb->admin_id!== 0){
                    $rmb->GetAdmin;
                }
            }
        }
        return response()->json(['remboursements'=>$rmbs]);
    }






}
