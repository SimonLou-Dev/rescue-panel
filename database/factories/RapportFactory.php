<?php

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\Intervention;
use App\Models\Patient;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rapport>
 */
class RapportFactory extends Factory
{


    protected $model = Rapport::class;



    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $interType = Intervention::first();
        $user = User::first();
        $hop = Hospital::first();

        return [
            'user_id' => User::factory(),
            'interType' => $interType->id,
            'patient_id' => Patient::factory(),
            'transport' => $hop->id,
            'description'=>$this->faker->text,
            'price'=>$this->faker->randomNumber(3),
            'started_at' => $this->faker->date(),
            'service' => $this->faker->boolean ? 'SAMS' : 'LSCoFD',
        ];
    }
}
