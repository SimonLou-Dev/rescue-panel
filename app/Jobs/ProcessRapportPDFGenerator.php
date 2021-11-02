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
use Illuminate\Support\Facades\Log;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\FilesystemException;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\RequestException;

class ProcessRapportPDFGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = array();
        $user = $this->rapport->GetUser->name;
        $rapport = $this->rapport;

        $client = new Client(env('PDF_ADDR'), new \Http\Adapter\Guzzle7\Client());

        ob_start();
        require(base_path('/resources/PDF/RI/index.php'));
        $content = ob_get_clean();

        $index = DocumentFactory::makeFromString('index.html', $content);
        $assets = [
            DocumentFactory::makeFromPath('LONG_EMS_BC_2.png', base_path('/resources/PDF/RI/LONG_EMS_BC_2.png')),
            DocumentFactory::makeFromPath('signature.png', base_path('/resources/PDF/RI/signature.png'))
        ];

        $pdf = new HTMLRequest($index);
        $pdf->setAssets($assets);

        try {
            $client->store($pdf, $this->path);
        } catch (ClientException | FilesystemException | RequestException | \Exception $e) {
            Log::critical($e);
        }
    }
}
