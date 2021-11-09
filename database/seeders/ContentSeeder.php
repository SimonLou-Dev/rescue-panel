<?php

namespace Database\Seeders;

use App\Models\BCType;
use App\Models\Hospital;
use App\Models\Intervention;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bc = new BCType();
        $bc->name = 'Fusillade';
        $bc->save();

        $inter = new Intervention();
        $inter->name = 'Black Code';
        $inter->save();

        $tsp = new Hospital();
        $tsp->name = 'Pas de transport';
        $tsp->save();
    }
}
