<?php

namespace App\Jobs;

use App\Events\Notify;
use App\Models\Rapport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\FilesystemException;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\RequestException;

class ProcessRapportPDFGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    protected $rapport;
    protected $path;

    /**
     * Create a new job instance.
     *
     * @param Rapport $rapport
     */
    public function __construct(Rapport $rapport, string $path)
    {
        $this->rapport = $rapport;
        $this->path = $path;

        $this->onQueue('pdfgeneration');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $user = $this->rapport->GetUser->name;
        $rapport = $this->rapport;

        $path = $this->path;

        ob_start();
        require(base_path('/resources/PDF/RI/index.php'));
        $content = ob_get_clean();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($content);
        $pdf->save(Storage::path($path));
    }
}
