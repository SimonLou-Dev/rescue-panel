<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogDb;
use Illuminate\Support\Facades\File;

class LogsController extends Controller
{

    private $fileList = [
        'rapports'=>'storage/logs/rapports.log',
        'facture'=>'storage/logs/facture.log',
        'accountCreated'=>'storage/logs/accountCreated.log',
        'testPoudre'=>'storage/logs/testPoudre.log',
        'bc'=>'storage/logs/bc.log',
        'grade_editing'=>'storage/logs/grade_editing.log',
        'service'=>'storage/logs/service.log',
        'demandes'=>'storage/logs/demandes.log',
        'contentMGT'=>'storage/logs/contentMGT.log',
        'sanctions'=>'storage/logs/sanctions.log'
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

    public function accountCreated(int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'accountCreated';
        $logs->desc = 'user n°' . $userid;
        $logs->save();
        File::append(base_path($this->fileList['accountCreated']), '[' . date('d/m/Y H:i:s') . '] registered user n°'. $userid . "\n");
    }

    public function FactureLogging(string $action, int $factureid, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'facture';
        $logs->desc = 'facture n°' . $factureid . ' ' . $action;
        $logs->save();
        File::append(base_path($this->fileList['facture']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' ' . $action . ' ' . ' facture n° ' . $factureid . "\n");
    }

    public function TestDePoudreLogging(int $pouderTestId, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'TDP';
        $logs->desc = 'TDP n°' . $pouderTestId . ' created';
        $logs->save();
        File::append(base_path($this->fileList['testPoudre']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' created ' . ' TDP n° ' . $pouderTestId . "\n");
    }

    public function BCLogging(string $action, int|null $bcId, int $userid){
        if(is_null($bcId)){
            $bcId = 0;
        }
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'Black Code';
        $logs->desc = 'BC n°' . $bcId . ' ' . $action;
        $logs->save();
        File::append(base_path($this->fileList['bc']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' ' . $action . ' ' . ' BC n° ' . $bcId . "\n");
    }

    public function GradeLogging(string $action, int $gradeId, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'grade';
        $logs->desc = 'grade n°' . $gradeId . ' ' . $action;
        $logs->save();
        File::append(base_path($this->fileList['grade_editing']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' ' . $action . ' ' . ' grade n° ' . $gradeId . "\n");
    }

    public function ServiceLogging(string $action, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'service';
        $logs->desc = 'Service de user n°' . $userid . ' ' . $action;
        $logs->save();
        File::append(base_path($this->fileList['service']), '[' . date('d/m/Y H:i:s') . '] service of user n°' . $userid . ' ' . $action .  "\n");
    }

    public function DemandesLogging(string $action, string $demandename, int $demandeId, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'demande';
        $logs->desc = 'demande n°' . $demandeId . " ${demandename} " . $action;
        $logs->save();
        File::append(base_path($this->fileList['demandes']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' ' . $action . ' ' . ' demande n° ' . $demandeId .  " ${demandename} " . "\n");
    }


    public function SanctionsLogging(string $action, int $targetId, int $userid){
        $logs = new LogDb();
        $logs->user_id = $userid;
        $logs->action = 'sanctions';
        $logs->desc = 'user n°' . $targetId . ' was ' . $action . " by ${userid}" ;
        $logs->save();
        File::append(base_path($this->fileList['sanctions']), '[' . date('d/m/Y H:i:s') . '] user ' . $userid . ' ' . $action . ' ' . ' user n° ' . $targetId . "\n");
    }

}
