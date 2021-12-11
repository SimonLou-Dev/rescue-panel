<?php

namespace Tests\Feature;

use App\Models\BCPatient;
use App\Models\Blessure;
use App\Models\CouleurVetement;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlesseControllerTest extends TestCase
{

    private $faker;


    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create('fr_FR');
    }



    /**
     * @return void
     */
    public function test_addPatient()
    {
        $user = TestTools::getUserInBc();
        $this->postJson('/data/blackcode/'. $user->bc_id .'/add/patient',TestTools::generatePatientInfos($this->faker))->assertStatus(201);
    }

    public function test_removePatient(){
        $user = TestTools::getUserInBc();
        $this->postJson('/data/blackcode/'. $user->bc_id .'/add/patient',TestTools::generatePatientInfos($this->faker));
        $patient_id = BCPatient::where('bc_id',$user->bc_id)->orderBy('id','desc')->first();
        $this->delete("/data/blackcode/delete/patient/".$patient_id->id)->assertStatus(200);
    }

    //generateListWithAllPatients
}
