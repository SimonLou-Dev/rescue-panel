<?php

namespace Tests\Feature;

use App\Models\Hospital;
use App\Models\Intervention;
use App\Models\Pathology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Expr\Array_;
use Tests\TestCase;

class RapportTest extends TestCase
{

    use WithFaker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    private function makeRapport(): array
    {
        $type = Intervention::where('service','SAMS')->first();
        $hosp = Hospital::where('service', 'SAMS')->first();
        $pathology = Pathology::first();
        return [
            'name'=>$this->faker->name,
            'startinter'=>$this->faker->date . ' ' . $this->faker->time('H:i'),
            'type'=>$type->id,
            'transport'=>$hosp->id,
            'desc'=>$this->faker->text(200),
            'payed'=>$this->faker->boolean,
            'montant'=>$this->faker->randomNumber(3),
            'ata'=>$this->faker->randomNumber(2).'h '.($this->faker->boolean ? $this->faker->randomNumber(2).'m' :''),
            'pathology'=>$pathology->id,
        ];

    }

    public function test_permMissRapport()
    {
        //service : off - any perm
        $user = TestTool::getLambdaUser('SAMS');
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_create', false);
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_HS', false);
        TestTool::logIn($user);
        $response = $this->postJson('/data/rapport/post',[
            'name'=>'',
            'startinter'=>'',
            'tel'=>'',
            'type'=>'',
            'transport'=>'',
            'desc'=>'',
            'payed'=>'',
            'montant'=>'',
            'ata'=>'',
        ]);
        $response->assertStatus(403);


        //service : on - perm any
        $user = TestTool::getLambdaUser('SAMS');
        $user->OnService = true;
        $user->save();
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_create', false);
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_HS', false);
        TestTool::logIn($user);
        $response = $this->postJson('/data/rapport/post',[
            'name'=>'',
            'startinter'=>'',
            'tel'=>'',
            'type'=>'',
            'transport'=>'',
            'desc'=>'',
            'payed'=>'',
            'montant'=>'',
            'ata'=>'',
        ]);
        $response->assertStatus(403);

        //service : of - perm rapport_HS
        $user = TestTool::getLambdaUser('SAMS');
        $user->OnService = false;
        $user->save();
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_create', false);
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_HS', true);
        TestTool::logIn($user);
        $response = $this->postJson('/data/rapport/post',[
            'name'=>'',
            'startinter'=>'',
            'tel'=>'',
            'type'=>'',
            'transport'=>'',
            'desc'=>'',
            'payed'=>'',
            'montant'=>'',
            'ata'=>'',
        ]);
        $response->assertStatus(403);
    }


    public function test_sendRapport()
    {
        //service : off - rapport_create, rapport_HS
        $user = TestTool::getLambdaUser('SAMS');
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_create', true);
        TestTool::setPermToGradeId($user->medic_grade_id, 'rapport_HS', true);
        TestTool::logIn($user);
        $rapport = self::makeRapport();
        $response = $this->postJson('/data/rapport/post', $rapport);
        $response->assertCreated();
        $this->assertDatabaseHas('Patients', [
            'name' => $rapport['name'],
        ]);
        $this->assertDatabaseHas('Rapports', [
            'description' => $rapport['desc'],
        ]);
    }
}
