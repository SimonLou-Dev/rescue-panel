<?php

namespace App\Jobs;

use App\Enums\DiscordChannel;
use App\Models\BCList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessEmbedBCGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
     private string $formated,
     private BCList $bc,
     private string $endername,
     private int|string $channIdentifier)
    {
        $this->onQueue('discord');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $formated = $this->formated;
        $bc = $this->bc;


        $number = count($bc->GetPatients);
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
                    'value'=>$number,
                    'inline'=>true,
                ],[
                    'name'=>'secouristes',
                    'value'=>count($bc->GetPersonnel),
                    'inline'=>true,
                ],[
                  'name'=>'pdf',
                  'inline'=>false,
                  'value'=>":link: [`PDF`](".env('APP_URL').'/pdf/bc/'.$bc->id . ")",
                ],[
                    'name'=>'Liste des patients',
                    'value'=>"nom | carte d'identité" .  ($bc->service === "SAMS" ? ' | couleur de vêtement' : ''),
                    'inline'=>false,
                ],
            ],
            'color'=>'10368531',
        ]);
        if($number != 0){
            if($number > 20){
                $finalembedslist = $this->manyPatientEmbed($number, $bc->GetPatients, $finalembedslist);
            }else{
                array_push($finalembedslist,$this::onePatientEmbed($bc->GetPatients, 1,1,0)[1]);
            }
        }

        $a = 0;
        $msg = "";
        while ($a < count($bc->GetPersonnel)){
            if($a == 0){
                $msg = 'Secouristes : ' . $bc->GetPersonnel[$a]->name;
            }else{
                $msg = $msg . ', ' . $bc->GetPersonnel[$a]->name;
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

        if(isset($bc->discord_msg_id)){
            Http::withHeaders([
                'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
            ])->patch("https://discord.com/api/v9/channels/".$this->channIdentifier . "/messages/".$bc->discord_msg_id,
                [
                    'embeds'=>$finalembedslist,
                    'content'=>'',
                ]
            );
        }else if(is_numeric($this->channIdentifier)){
            Http::withHeaders([
                'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
            ])->post("https://discord.com/api/v9/channels/".$this->channIdentifier."/messages",
            [
                'embeds'=>$finalembedslist,
                'content'=>'',
            ]);
        }else {
            Http::post($this->channIdentifier,[
                'username'=> "rescue panel",
                'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
                'embeds'=>$finalembedslist,
                'content'=>'',
            ]);
        }


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
            $msg = $msg . ' '. $patients[$item]->name . ' ' . ($patients[$item]->idcard ? ':white_check_mark:' : ':x:') . (!is_null($patients[$item]->couleur) ? ' ' . $patients[$item]->GetColor->name  : '') . " \n";
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
