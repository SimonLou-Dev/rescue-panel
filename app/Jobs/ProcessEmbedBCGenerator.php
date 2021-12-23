<?php

namespace App\Jobs;

use App\Http\Controllers\BlackCodes\BcEmbedController;
use App\Models\BCList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProcessEmbedBCGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    protected $formated;
    protected $patients;
    protected $personnels;
    protected $bc;
    protected $endername;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $formated, object $patients, object $personnels,BCList $bc, string $endername)
    {
        $this->formated = $formated;
        $this->patients = $patients;
        $this->personnels = $personnels;
        $this->bc = $bc;
        $this->endername = $endername;

        $this->onQueue('discord');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $patients = $this->patients;
        $formated = $this->formated;
        $personnels = $this->personnels;
        $bc = $this->bc;


        $number = count($patients);
        $finalembedslist = array();
        array_push($finalembedslist,[
            'title'=>'Fin du Black Code #' . $bc->id .' :',
            'fields'=>[
                [
                    'name'=>'lieux',
                    'value'=>$bc->place,
                    'inline'=>false,
                ],[
                    'name'=>'patients',
                    'value'=>count($patients),
                    'inline'=>true,
                ],[
                    'name'=>'secouristes',
                    'value'=>count($personnels),
                    'inline'=>true,
                ],[
                    'name'=>'Liste des patients',
                    'value'=>"nom | carte d'identité | couleur de vêtement",
                    'inline'=>false,
                ],
            ],
            'color'=>'10368531',
        ]);
        if($number != 0){
            if($number > 20){
                $finalembedslist = $this->manyPatientEmbed($number, $patients, $finalembedslist);
            }else{
                array_push($finalembedslist,$this::onePatientEmbed($patients, 1,1,0)[1]);
            }
        }

        $a = 0;
        $msg = "";
        while ($a < count($personnels)){
            if($a == 0){
                $msg = 'Secouristes : ' . $personnels[$a]->name;
            }else{
                $msg = $msg . ', ' . $personnels[$a]->name;
            }
            $a++;
        }

        array_push($finalembedslist,[
            'title'=>'---------------',
            'color'=>'10368531',
            'description'=>$msg,
            'fields'=>[
                [
                    'name'=>'Lancé par :',
                    'value'=>$bc->GetUser->name,
                    'inline'=>true,
                ],[
                    'name'=>'cloturé par :',
                    'value'=>$this->endername,
                    'inline'=>true,
                ],[
                    'name'=>'durée :',
                    'value'=>$formated,
                    'inline'=>true,
                ]
            ]
        ]);

        Http::post(env('WEBHOOK_PU'),[
            'username'=> "LSCoFD - MDT",
            'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
            'embeds'=>$finalembedslist,
        ]);
    }

    private function manyPatientEmbed(int $number, object $patients, array $finalembedslist): array
    {
        $nbr = $number-1;
        $pages = (int) ceil($number/20);
        $page = 1;
        $a = 0;
        while($a < $nbr){
            $embed = ProcessEmbedBCGenerator::onePatientEmbed($patients, $page, $pages, $a);
            array_push($finalembedslist,$embed[1]);
            $a = $embed[0];
            $page++;
        }

        return $finalembedslist;
    }

    private static function onePatientEmbed(object $patients,int $page,int $pages, $a): array
    {
        $embedpatient = array();
        $b = 0;
        $msg = "";
        $max = 0;
        if(count($patients) - $a > 19){
            $max = 20;
        }else{
            $max = count($patients) - $a;
        }

        while($b < $max){
            $item = $b +$a;
            $msg = $msg . ' '. $patients[$item]->name . ' ' . ($patients[$item]->idcard ? ':white_check_mark:' : ':x:') . ' ' . $patients[$item]->GetColor->name . " \n";
            $b++;
        }
        $embedpatient = [
            'title'=>'Liste des patients '. $page .'/'.$pages,
            'color'=>'10368531',
            'description'=>$msg
        ];
        return [$a+19, $embedpatient];
    }

}
