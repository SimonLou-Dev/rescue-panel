<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Patient::class;

    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'tel'=>'555-'.$this->faker->randomNumber(3),
            'naissance' => $this->faker->date,
            'living_place' => $this->faker->city,
            'blood_group' => $this->faker->bloodGroup()
        ];
    }
}
