<?php


namespace App\PDFExporter;


use Maatwebsite\Excel\Concerns\FromArray;

class ServicePDFExporter implements FromArray
{

    protected $final;

    public function __construct(array $final)
    {
        $this->final = $final;
    }

    public function array(): array
    {
        return $this->final;
    }

}
