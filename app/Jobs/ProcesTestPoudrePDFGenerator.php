<?php

namespace App\Jobs;

use App\Models\TestPoudre;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcesTestPoudrePDFGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $test;
    protected $path;

    /**
     * Create a new job instance.
     *
     * @param TestPoudre $test
     * @param string $path
     */
    public function __construct(TestPoudre $test, string $path)
    {
        $this->test = $test;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->test->GetPersonnel;
        $user=$user->name;
        $test = $this->test;
        $path = $this->path;

        ob_start();
        require(base_path('/resources/PDF/test/poudre.php'));
        $content = ob_get_clean();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($content);
        $pdf->save(Storage::path($path));

    }
}
