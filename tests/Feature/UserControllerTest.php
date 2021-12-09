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
        $pseudo = $this->faker->firstName() . ' ' . $this->faker->lastName();
        $data = [
            'pseudo'=>  $pseudo,
            'email'=>$this->faker->email(),
            'psw'=> $pseudo,
        ];

        $req = $this->postJson('/data/register', $data);
        $req->assertStatus(201);
    }

    public function test_postInfos(){
        if(!\Auth::check()){
            \Auth::login(User::orderBy('id', 'Desc')->first());
        }

        $data = [
            'compte'=>$this->faker->numberBetween(111,9999999),
            'tel'=>$this->faker->numberBetween(111111,9999999),
            'living'=>'LS',
        ];
        $req = $this->postJson('/data/postuserinfos', $data);
        $user = User::orderBy('id', 'Desc')->first();
        $user->grade_id = 2;
        $user->save();
        $req->assertStatus(201);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @covers \App\Http\Controllers\Rapports\UserController::login
     */
    public function test_login(){
        $user = User::orderBy('id', 'Desc')->first();

        $data = [
            'email'=>$user->email,
            'psw'=>$user->name,
        ];


        $req = $this->postJson('/data/login', $data);
        $req->assertStatus(200);
    }



}
