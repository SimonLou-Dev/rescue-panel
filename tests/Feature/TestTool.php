<?php

namespace Tests\Feature;

use App\Models\User;

class TestTool {

    public static function getAnyAccessUser(string $service): User
    {
        $user = User::factory()->make();

        if($service === 'SAMS'){
            $user->medic_grade_id = 1;
            $user->medic = true;
        }else{
            $user->fire_grade_id = 1;
            $user->fire = true;
        }

        $user->service = $service;
        $user->save();

        return $user;

    }

    public static function getAdminUser(string $service):User
    {

        $user = User::factory()->make();

        if($service === 'SAMS'){
            $user->medic_grade_id = 5;
            $user->medic = true;
        }else{
            $user->fire_grade_id = 4;
            $user->fire = true;
        }

        $user->service = $service;
        $user->save();

        return $user;
    }

    public static function getLambdaUser(string $service):User
    {

        $user = User::factory()->make();

        if($service === 'SAMS'){
            $user->medic_grade_id = 2;
            $user->medic = true;
        }else{
            $user->fire_grade_id = 3;
            $user->fire = true;
        }

        $user->service = $service;
        $user->save();

        return $user;

    }



}
