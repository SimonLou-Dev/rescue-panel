<?php

namespace App\Console\Commands;

use App\Models\DayService;
use App\Models\WeekService;
use Illuminate\Console\Command;
use App\Models\User;

class StartWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add line to all user for service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $week =  date('W', time());
        $users = User::all();
        $dayservice = WeekService::where('week_number', $week)->get('user_id');
        $b = 0;
        $array = array();
        while($b < count($dayservice)){
            array_push($array, $dayservice[$b]->user_id);
            $b++;
        }
        $a = 0;
        $datas = array();

        foreach($users as $usr){
            if(($usr->isInFireUnit() || $usr->isInMedicUnit()) && !in_array($usr->id, $array) && !$usr->dev){
                if($usr->isInFireUnit()){
                    array_push($datas, ['week_number'=>$week, 'user_id'=>$users[$a]->id, 'service'=>'LSCoFD']);
                }
                if($usr->isInMedicUnit()){
                    array_push($datas, ['week_number'=>$week, 'user_id'=>$users[$a]->id, 'service'=>'SAMS']);
                }
            }
        }

        WeekService::insert($datas);
        $this->info('Inserted');
        return 0;
    }
}
