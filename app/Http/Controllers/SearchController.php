<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchPatient(string $test){



        $patients = Patient::where('name', $test);
        if(is_numeric($test)){
            $patients = Patient::where('id', $test)->get();
        }else{
            $patients = Patient::search($test)->get()->take(5);
        }

        return response()->json([
            'patients'=>$patients
        ]);
    }

    public function searchUser(string $test){
        $users = User::search($test)->get()->take(5);
        return response()->json([
            'users'=>$users
        ]);
    }
}
