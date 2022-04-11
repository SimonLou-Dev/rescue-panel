<?php

namespace App\Console\Commands;

use App\Http\Controllers\Service\ServiceGetterController;
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
        $this->info('Starting User clening');
        $base = User::count();
        $users = User::where('fire', false)->where('medic','false')->where('moderator',false)->get();
        foreach ($users as $user){
            $user->delete();
        }
        $final = $base - User::count();
        $this->info($final . ' user(s) deleted');
        $this->info('Creating WeekService Lines');
        $base = WeekService::count();
        $week = ServiceGetterController::getWeekNumber();
        $users = User::all();
        $dayserviceFire = WeekService::where('week_number', $week)->where('service','LSCoFD')->get('user_id');
        $dayserviceMedic = WeekService::where('week_number', $week)->where('service','SAMS')->get('user_id');
        $FireArray = array();
        $MedicArray = array();
        foreach ($dayserviceFire as $fire){
            array_push($FireArray, $fire->user_id);
        }
        foreach ($dayserviceMedic as $medic){
            array_push($MedicArray, $medic->user_id);
        }
        $datas = array();


        foreach($users as $usr){
            if(($usr->isInFireUnit() || $usr->isInMedicUnit()) && !$usr->dev){
                if($usr->isInFireUnit() && !in_array($usr->id, $FireArray)){
                    array_push($datas, ['week_number'=>$week, 'user_id'=>$usr->id, 'service'=>'LSCoFD']);
                }
                if($usr->isInMedicUnit() && !in_array($usr->id, $MedicArray)){
                    array_push($datas, ['week_number'=>$week, 'user_id'=>$usr->id, 'service'=>'SAMS']);
                }
            }
        }
        WeekService::insert($datas);
        $final = $base - WeekService::count();
        $this->info( $final . ' line(s) inserted');
        $this->info('Importing for search');
        $this->callSilently('scout:import', ['model'=>'App\Models\WeekService']);
        $this->info('All entry of WeekService are imported');
        return 0;
    }
}
