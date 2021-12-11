<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserGradeControllerTest extends TestCase
{


    private $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create('fr_FR');
    }

    public function test_userGradeSetterUp(){
        $user = TestTools::GetAllPermUserAuthed();

        $gradeid = $this->faker->numberBetween(2,9);
        $userid = $this->faker->numberBetween(1,$user->id);

        $req = $this->post('/data/users/setgrade/'. $gradeid .'/' . $userid);
        $req->assertStatus(200)->assertJson(['status'=>'OK']);
    }

    public function test_getUserPerm(){
        $user = TestTools::GetAllPermUserAuthed();
        $this->get('/data/getperm')->assertStatus(200);
    }

    public function test_getAllGrades(){
        $user = TestTools::GetAllPermUserAuthed();
        $this->get('/data/admin/grades/get')->assertStatus(200);
    }

    public function test_changePerm()
    {
        $user = TestTools::GetAllPermUserAuthed();
        $gradeid = $this->faker->numberBetween(2,9);
        $permid = $this->faker->numberBetween(1,30);

        $this->put('/data/admin/grades/perm_'. $permid .'/'.$gradeid)->assertStatus(201);
    }




}
