<?php

namespace App\Console\Commands;

use App\Models\LogDb;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use ZipArchive;

class DayLogsCleanning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info('Starting logs cleaning');
        $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s ',time()) . ' 48 hours'));
        $this->info('DB is cleared from logs who date <= ' . $date);
        $logs = LogDb::where('created_at', '<=', $date)->get();
        foreach ($logs as $log){
           // $log->delete();
        }
        $this->info('DB Cleared');
        $zip = new \ZipArchive();
        $fileName = 'logs_'.date('d-m-Y-H-I').'.zip';
        $ListOffiles = array();

        if ($zip->open(storage_path('app/public/logs/'.$fileName), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files(base_path('storage/logs'));
            foreach ($files as $value) {
                if($value->getExtension() === "log"){
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                    array_push($ListOffiles, $value->getPathname());
                }

            }

            if($zip->numFiles != 0){
                $zip->close();
            }

        }
        $this->info('Zip created in ' . storage_path('app/public/logs/'.$fileName));
        $this->info('recreating new files & deleting alter');



        foreach($ListOffiles as $alt){
            File::delete($alt);
            File::put($alt, '');

        }
        shell_exec('chown www-data /var/www/storage/logs/*');
        shell_exec('chmod 777 /var/www/storage/logs/*');

        $this->info('logs saved');



    }
}
