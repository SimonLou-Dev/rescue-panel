<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;


class UserTest extends TestCase
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

    public function test_registerCallBack()
    {

        $discord_id = ''. $this->faker->randomNumber(9).$this->faker->randomNumber(9);
        $mail = $this->faker->unique()->safeEmail;
        $req = "/auth/fake?id=". $discord_id ."&email=".$mail;


        $response = $this->getJson($req);
        $this->assertDatabaseHas('Users', [
            'discord_id' => $discord_id,
        ]);

        $response->assertStatus(302);

    }

    public function test_sendUserInfos()
    {

        if(!\Auth::check()){
            $discord_id = ''. $this->faker->randomNumber(9).$this->faker->randomNumber(9);
            $mail = $this->faker->unique()->safeEmail;

            $user = TestTool::getUserWithAnyInfos($discord_id, $mail);
            \Auth::login($user);
            Session::push('user', $user);
        }

        $name = $this->faker->name;

        $response = $this->postJson('/data/postuserinfos', [
            'compte'=>$this->faker->randomNumber(5),
            'tel'=>'555-'.$this->faker->randomNumber(3),
            'name'=>$name,
            'staff'=>0,
            'service'=>$this->faker->boolean ? 'SAMS' : 'LSCoFD',
            'living'=>$this->faker->city,
        ]);


        $this->assertDatabaseHas('Users', [
            'name' => $name,
        ]);

        $response->assertStatus(201);

    }

    public function test_userLogin()
    {

        TestTool::setPermToGradeId(2, 'access', true);
        TestTool::setPermToGradeId(3, 'access', true);

        TestTool::logOut();
        $user = TestTool::getLambdaUser('SAMS');


        $response = $this->getJson('/auth/fake?id='. $user->discord_id ."&email=" . $user->email);
        $response->assertRedirect('/dashboard');



        TestTool::logOut();
        $user = TestTool::getLambdaUser('LSCoFD');
        $response = $this->getJson('/auth/fake?id='. $user->discord_id ."&email=" . $user->email);
        $response->assertRedirect('/dashboard');

    }

    public function test_userLogout()
    {


        $user = TestTool::getLambdaUser('SAMS');
        TestTool::logIn($user);

        $response = $this->getJson('/logout');
        $response->assertRedirect('/login');

    }


}
