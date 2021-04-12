<?php

namespace App\Console\Commands;

use App\Models\DayService;
use App\Models\WeekService;
use Illuminate\Console\Command;

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
        $users = \App\PDFExporter\Models\User::where('grade', '>', 1)->get();
        $dayservice = WeekService::where('week', $week)->get('user_id');
        $b = 0;
        $array = array();
        while($b < count($dayservice)){
            array_push($array, $dayservice[$b]->user_id);
            $b++;
        }
        $a = 0;
        $datas = array();
        while ($a < count($users)){
            if(!in_array($users[$a]->id, $array)){
                array_push($datas, ['week'=>$week, 'user_id'=>$users[$a]->id]);
            }
            $a++;
        }
        WeekService::insert($datas);
        $this->info('Inserted');
        return 0;
    }
}
