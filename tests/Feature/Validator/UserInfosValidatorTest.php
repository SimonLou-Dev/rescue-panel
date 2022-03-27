<?php

namespace Tests\Feature\Validator;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\Feature\TestTool;
use Tests\TestCase;

class UserInfosValidatorTest extends TestCase
{

    use WithFaker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function test_sendUserInfosValidator()
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
            'compte'=>$this->faker->word(),
            'tel'=>'555'.$this->faker->randomNumber(3),
            'name'=>$this->faker->word(),
            'staff'=>null,
            'service'=>null,
            'living'=>'',
        ]);



        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['compte','tel','name','staff','service','living']);

    }

}
