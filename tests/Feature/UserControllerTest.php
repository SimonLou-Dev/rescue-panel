<?php

namespace Tests\Feature;


use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    private $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @covers \App\Http\Controllers\Rapports\UserController::register
     */

    public function test_register()
    {
        $data = [
            'pseudo'=>  $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email'=>$this->faker->email(),
            'psw'=>$this->faker->password(),
        ];

        $req = $this->postJson('/data/register', $data);
        $req->assertStatus(201);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @covers \App\Http\Controllers\Rapports\UserController::login
     */
    public function test_login(){
        $data = [
            'pseudo'=>  $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email'=>$this->faker->email(),
            'psw'=>$this->faker->password(),
        ];

        $createuser = new User();
        $createuser->name = $data['pseudo'];
        $createuser->email = $data['email'];
        $createuser->password = Hash::make($data['psw']);
        $createuser->grade_id = 2;
        $createuser->save();


        $req = $this->postJson('/data/register', $data);
        $req->assertStatus(200);
    }
}
