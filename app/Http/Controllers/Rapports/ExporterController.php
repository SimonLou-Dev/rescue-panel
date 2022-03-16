<?php

namespace App\Http\Controllers\Rapports;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessRapportPDFGenerator;
use App\Models\Facture;
use App\Models\Rapport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\FilesystemException;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\RequestException;

class ExporterController extends Controller
{

    public function makeRapportPdf(Request $request, int $id){


        $rapport = Rapport::where('id', $id)->first();


        $path =  storage_path('app/public/RI/') . $rapport->id.'.pdf';
        $user = $rapport->GetUser;


        if(!Storage::exists($path)){
            $pdf = Pdf::loadView('pdf.RI',['rapport'=>$rapport, 'user'=>$user]);
            $pdf->save($path);
            return $pdf->stream();
        }else{
            return \response()->file($path);
        }

    }

    public function makeImpayPdf(Request $request, string $from , string $to){

        $this->authorize("export", Facture::class);
        $impaye = Facture::where('payed', 0)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->orderBy('id', 'desc')->get();
        $total = 0;
        $impaye = $impaye->filter(function ($item, $key){
           return $item->price != 0;
        });
        foreach ($impaye as $imap){
            $total = $total + $imap->price;
        }

        $infos = ['from'=>date('d/m/Y', strtotime($from)),'to'=>date('d/m/Y', strtotime($to)), 'total'=>$total];
        $pdf = Pdf::loadView('pdf.facture',['infos'=>$infos, 'factures'=>$impaye]);


        return $pdf->stream();

    }
}
