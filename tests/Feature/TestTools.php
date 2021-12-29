<?php

namespace Tests\Feature;
use App\Models\BCList;
use App\Models\Blessure;
use App\Models\CouleurVetement;
use App\Models\Grade;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class TestTools
{


    public static function getUserInBc(): User
    {
        $user = User::orderBy('id','desc')->first();
        $user->grade_id = 14;
        $bc = BCList::where('ended',false)->orderBy('id','desc')->first();
        $user->bc_id = $bc->id;
        $user->save();
        if(\Auth::check()){
            \Auth::logout();
        }
        \Auth::login($user);
        Session::push('user_grade', $user->GetGrade);
        return $user;
    }

    /**
     * @return User
     */
    public static function getUserOutBc(): User
    {
        $user = User::orderBy('id','desc')->first();
        $user->grade_id = 14;
        $user->save();
        if(\Auth::check()){
            \Auth::logout();
        }
        \Auth::login($user);
        Session::push('user_grade', $user->GetGrade);
        if(!is_null($user->bc_id)){
            $user->bc_id = null;
            $user->save();
        }
        return $user;
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public static  function generatePatientInfos(Generator $faker):array
    {
        $blessures = Blessure::orderByDesc('name')->first();
        $color = CouleurVetement::orderByDesc('name')->first();
        return [
            'name'=> $faker->firstName . ' ' . $faker->lastName,
            'blessure'=>$blessures->id,
            'carteid'=>$faker->boolean,
            'color'=>$color->id,
            'payed'=>$faker->boolean,
        ];

    }

    public static function GetAllPermUserAuthed() :User
    {
        $user = User::orderBy('id','desc')->first();
        $grade = Grade::orderBy('id','desc')->first();
        if($user->grade_id == $grade->id){
            $user->grade_id = $grade->id;
            $user->save();
        }
        if(!\Auth::check()){
            \Auth::login($user);
        }
        return $user;
    }
}
