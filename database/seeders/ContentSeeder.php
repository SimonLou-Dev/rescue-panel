<?php

namespace Database\Seeders;

use App\Models\BCType;
use App\Models\Blessure;
use App\Models\Hospital;
use App\Models\Intervention;
use App\Models\Pathology;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    private $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    private array $services = ['LSCoFD','OMC'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->services as $service){
            $blessure = new Blessure();
            $blessure->name = 'BPB';
            $blessure->service = $service;
            $blessure->save();

            $tsp = new Hospital();
            $tsp->name = 'Pas de transport';
            $tsp->service = $service;
            $tsp->save();

            $inter = new Intervention();
            $inter->name = 'Black Code';
            $inter->service = $service;
            $inter->save();

            $inter = new Intervention();
            $inter->name = 'Régulière';
            $inter->service = $service;
            $inter->save();

        }

        $bc = new BCType();
        $bc->name = $this->faker->word;
        $bc->save();

        $patho = new Pathology();
        $patho->name = 'Test';
        $patho->desc = $this->faker->text(200);
        $patho->stock_item = json_encode([]);
        $patho->save();


    }
}
