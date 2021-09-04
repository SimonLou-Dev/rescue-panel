<?php

namespace App\Http\Controllers\Rapports;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\FilesystemException;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\RequestException;

class ExporterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function makeRapportPdf(Request $request, int $id){
        $data = array();

        $rapport = Rapport::where('id', $id)->first();
        $path = base_path('public/storage/RI/'. $rapport->id . ".pdf");

        if(!file_exists($path)){

            $user = $rapport->GetUser->name;

            $client = new Client(env('PDF_ADDR'), new \Http\Adapter\Guzzle7\Client());

            ob_start();
            require(base_path('/resources/PDF/RI/index.php'));
            $content = ob_get_clean();

            $index = DocumentFactory::makeFromString('index.html', $content);
            $assets = [
                DocumentFactory::makeFromPath('LONG_EMS_BC_2.png', base_path('/resources/PDF/RI/LONG_EMS_BC_2.png')),
                DocumentFactory::makeFromPath('signature.png', base_path('/resources/PDF/RI/signature.png'))
            ];

            $pdf = new HTMLRequest($index);
            $pdf->setAssets($assets);
            try {
                $client->store($pdf, $path);
            } catch (ClientException | FilesystemException | RequestException | \Exception $e) {
                Log::critical($e);
            }
        }

        return \response()->file($path);
    }

    public function makeImpayPdf(Request $request, string $from , string $to){
        //2021-01-05
        $impaye = Facture::where('payed', false)->where('created_at', '>=', $from)->where('created_at', '=<', $to)->orderBy('id', 'desc')->get();

        $infos = ['from'=>date('d/m/Y', strtotime($from)),'to'=>date('d/m/Y', strtotime($to))];
        $data = ['infos'=>$infos, 'impaye'=>$impaye];


        $client = new Client(env('PDF_ADDR'), new \Http\Adapter\Guzzle7\Client());

        ob_start();
        require(base_path('/resources/PDF/facture/index.php'));
        $content = ob_get_clean();

        $index = DocumentFactory::makeFromString('index.html', $content);
        $assets = [
            DocumentFactory::makeFromPath('LONG_EMS_BC_2.png', base_path('/resources/PDF/facture/LONG_EMS_BC_2.png'))
        ];

        $pdf = new HTMLRequest($index);
        $pdf->setAssets($assets);
        $path = base_path('public/storage/temp/factures/facture.pdf');
        try {
            $client->store($pdf, $path);
        } catch (ClientException | FilesystemException | RequestException | \Exception $e) {
            Log::critical($e);
        }
        return \response()->file($path);

    }
}
