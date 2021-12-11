<?php

namespace Tests\Feature;

use App\Models\BCList;
use App\Models\BCPatient;
use App\Models\BCType;
use App\Models\Blessure;
use App\Models\CouleurVetement;
use App\Models\User;
use Faker\Factory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BCControllerTest extends TestCase
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
    public function test_getUserInfosOutBc()
    {
        TestTools::getUserOutBc();
        $this->get('/data/blackcode/mystatus')->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_getMainPage()
    {
        TestTools::getUserOutBc();
        $req = $this->getJson('/data/blackcode/load');
        $req->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                ->has('active')
                ->has('ended')
                ->has('types')
        )->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_addBc(){
        TestTools::getUserOutBc();
        $type = BCType::where('id',1)->first();
        $request = [
            'type'=>$type->id,
            'place'=>$this->faker->country,
        ];
        $req = $this->postJson('/data/blackcode/create',$request);
        $req->assertStatus(201);
    }

    /**
     * @return void
     */
    public function test_getUserInfosInBc()
    {
        TestTools::getUserInBc();
        $this->get('/data/blackcode/mystatus')->assertStatus(200);
    }

    // endBc

    //generateRapport







}
