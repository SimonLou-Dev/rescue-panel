<?php

namespace App\Http\Controllers\BlackCodes;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\BCList;
use App\Models\FireReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FireReportController extends Controller
{

    public function postFireReport(Request $request, int $id){
        $this->authorize('close', BCList::class);
        $bc = BCList::where('id', $id)->firstOrFail();
        if($bc->service == 'SAMS') return response()->json([],404);
        $request->validate([
            'property'=>['int','required'],
            'compte'=>['size:2','required','string'],
            'type'=>['int','min:1']
        ]);
        $report = new FireReport();
        $report->bc_id=$bc->id;
        $report->property_number= $request->property;
        $report->compte= $request->compte;
        $report->type_id = $request->type;
        $report->save();
        event(new Notify('Rapport de feux enregistré',1,\Auth::user()->id));
        BCController::closBlackCode($bc);

        $a = 0;
        $msg = "";
        while ($a < count($bc->GetPersonnel)){
            if($a == 0){
                $msg = $bc->GetPersonnel[$a]->name;
            }else{
                $msg = $msg . ', ' . $bc->GetPersonnel[$a]->name;
            }
            $a++;
        }

        $embed = [
            [
                'title'=>'Rapport d\'incendie LSCoFD  (BC #' . $bc->id . ') :',
                'fields'=>[
                    [
                        'name'=>'Type d\'incendie :',
                        'value'=>$report->GetType->name,
                        'inline'=>true,
                    ],
                    [
                        'name'=>'Date :',
                        'value'=>date('d/m/Y H:i', strtotime($report->created_at)),
                        'inline'=>true,
                    ],
                    [
                        'name'=>'Numéro de propriété :',
                        'value'=>$report->property_number . ' ' . $report->compte,
                        'inline'=>false,
                    ],
                    [
                        'name'=>'Rapport d\'intervention :',
                        'value'=>$bc->description,
                        'inline'=>false,
                    ],
                    [
                        'name'=>'Intervenants :',
                        'value'=>$msg,
                        'inline'=>false,
                    ],[
                        'name'=>'PDF',
                        'value'=>":link: [`PDF`](".env('APP_URL').'/pdf/arson/'.$report->id . ")"
                    ]
                ],
                'color'=>'16775936',
                'footer'=> [
                    'text' => 'Information de : ' . Auth::user()->name
                ]
            ]
        ];

        \Discord::postMessage(DiscordChannel::FireReport, $embed);

        return response()->json([],202);
    }

    public function getFireReportList(Request $request){
        $queryPage = (int) $request->query('page');
        $readedPage = ($queryPage ?? 1) ;
        $reports = FireReport::search($request->query('query'))->get();
        foreach ($reports as $report){
            $report->GetBC;
            $report->GetType;
        }
        $finalList = $reports->skip(($readedPage-1)*20)->take(20);
        $url = $request->url() . '?query='.urlencode($request->query('query')).'&page=';
        $totalItem = $reports->count();
        $valueRounded = ceil($totalItem / 20);
        $maxPage = (int) ($valueRounded == 0 ? 1 : $valueRounded);
        //Creation of Paginate Searchable result
        $array = [
            'current_page'=>$readedPage,
            'last_page'=>$maxPage,
            'data'=> $finalList,
            'next_page_url' => ($readedPage === $maxPage ? null : $url.($readedPage+1)),
            'prev_page_url' => ($readedPage === 1 ? null : $url.($readedPage-1)),
            'total' => $totalItem,
        ];
        return response()->json([
            'reports'=>$array
        ]);
    }

    public function exportFireReport(int $id){
        $report = FireReport::where('id',$id)->first();
        $pdf = Pdf::loadView('pdf.ARSON',['report'=>$report]);

        return $pdf->stream();
    }

}
