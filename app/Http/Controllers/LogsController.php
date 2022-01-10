<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogDb;
use Illuminate\Support\Facades\File;

class LogsController extends Controller
{

    private $fileList = [
        'rapports'=>'storage/logs/rapports.log',
        'facture'=>'storage/logs/facture.log'
    ];

    public function __construct(){
        foreach ($this->fileList as $file){
            if(!File::exists(base_path($file))){
                File::put(base_path($file), '');
            }
        }
    }


    public function RapportLogging(string $action, int $rapportid, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'rapports';
        $logs->desc = 'rapports n°' . $rapportid . ' ' . $action;
        $logs->save();
        File::append(base_path($this->fileList['rapports']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' ' . $action . ' ' . ' rapports n° ' . $rapportid . "\n");
    }

    public function FactureLogging(string $action, int $factureid, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'facture';
        $logs->desc = 'facture n°' . $factureid . ' ' . $action;
        $logs->save();
        File::append(base_path($this->fileList['facture']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' ' . $action . ' ' . ' facture n° ' . $factureid . "\n");
    }
}
