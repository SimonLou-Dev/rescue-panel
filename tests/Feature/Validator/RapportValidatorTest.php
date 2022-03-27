<?php

namespace Tests\Feature\Validator;

use App\Models\Hospital;
use App\Models\Intervention;
use App\Models\Pathology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\TestTool;
use Tests\TestCase;

class RapportValidatorTest extends TestCase
{

    use WithFaker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sendRapportValidator()
    {

        $rapport =  [
            'name'=>$this->faker->randomLetter,
            'startinter'=>$this->faker->date(),
            'type'=>0,
            'transport'=>0,
            'desc'=>null,
            'payed'=>null,
            'montant'=>null,
            'ata'=>$this->faker->randomNumber(2).'h'.$this->faker->randomNumber(2).'m',
            'pathology'=>'aze',
            'bloodgroup'=>$this->faker->bloodType() . ' ' . $this->faker->bloodRh(),
            'ddn'=>$this->faker->date('Y/m/d'),
            'tel'=>$this->faker->randomNumber(5),
        ];
        //TODO detect ATA

        //service : off - rapport_create, rapport_HS
        $user = TestTool::getLambdaUser('SAMS');
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_create', true);
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_HS', true);
        TestTool::logIn($user);
        $response = $this->postJson('/data/rapport/post', $rapport);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(array_keys($rapport));

    }
}
