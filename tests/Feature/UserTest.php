<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class UserTest extends TestCase
{

    use WithFaker;

    public function __construct()
    {

        $this->setUpFaker();
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
        $req = "/auth/callback?id=". $discord_id ."&email=".$mail;


        $response = $this->get($req);
        //dd($response);
        $this->assertDatabaseHas('Users', [
            'discord_id' => $discord_id,
        ]);



        $response->assertStatus(302);

    }
}
