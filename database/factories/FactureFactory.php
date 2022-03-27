<?php

namespace Database\Factories;

use App\Models\Facture;
use App\Models\Patient;
use App\Models\Rapport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facture>
 */
class FactureFactory extends Factory
{





    protected $model = Facture::class;

    /**
     * @param int $patient_id
     */


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'rapport_id' => Rapport::factory(),
            'payed' => $this->faker->boolean,
            'price' => function (array $att) {
                return Rapport::find($att["rapport_id"])->first()->price;
            }
        ];
    }
}
