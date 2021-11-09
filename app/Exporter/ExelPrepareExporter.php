<?php


namespace App\Exporter;


use Maatwebsite\Excel\Concerns\FromArray;

class ExelPrepareExporter implements FromArray
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
