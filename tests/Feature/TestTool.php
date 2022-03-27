<?php

namespace Tests\Feature;

use App\Models\Facture;
use App\Models\Grade;
use App\Models\Patient;
use App\Models\Rapport;
use App\Models\User;
use Database\Factories\PatientFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

    public static function getUserWithAnyInfos(string $discordId, string $email): User
    {
        $user = new User();
        $user->email = $email;
        $user->discord_id = $discordId;
        $user->password = Hash::make('zaeaze45');
        $user->save();
        return $user;
    }

    public static function setPermToGradeId(int $gradeId, string $perm, bool $authorized):void
    {
        $grade = Grade::where('id',$gradeId)->first();
        $grade[$perm] = $authorized;
        $grade->save();

    }

    public static function logOut(): void
    {
        if(Auth::check()){
            Session::forget('service');
            Session::forget('user');
            Auth::logout();
            Session::flush();
            Session::invalidate();
            Session::regenerateToken();
        }
    }

    public static function logIn(User $user): void
    {
        if(Auth::check()) self::logOut();

        Auth::login($user);
        Session::push('user', $user);
        if(!is_null($user->service)){
            Session::push('service', $user->service);
        }
    }

    public static function createPatient(): Patient
    {
        $patient = Patient::factory()->make();
        $patient->save();

        return $patient;
    }

    public static function createRapport(): Rapport
    {

        //Creating and save rapport
        $rapport = Rapport::factory()
            ->has(Patient::factory()->count(1), 'GetPatient')
            ->has(Facture::factory()->count(1)->state(function (array $att, Rapport $rapport){
                return ['rapport_id' => $rapport->id, 'patient_id'=>$rapport->GetPatient->id];
            }), 'GetFacture')
            ->create();


        return $rapport;
    }



}
