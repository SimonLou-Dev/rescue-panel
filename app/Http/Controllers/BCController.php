<?php

namespace App\Http\Controllers;


use App\Models\BCList;
use App\Models\BCType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BCController extends Controller
{

    public function getUserInfos(): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        return response()->json([
            'status'=>'OK',
            'bc'=>$user->bc_id
        ]);
    }

    public function getMainPage(): \Illuminate\Http\JsonResponse
    {
        $ActiveBc = BCList::where('ended', false)->get();
        $a = 0;
        while ($a < count($ActiveBc)){
            $ActiveBc[$a]->GetUser;
            $ActiveBc[$a]->GetType;
            $ActiveBc[$a]->GetPatients;
            $ActiveBc[$a]->GetPersonnel;
            $ActiveBc[$a]->patients = count($ActiveBc[$a]->GetPatients);
            $ActiveBc[$a]->secouristes = count($ActiveBc[$a]->GetPersonnel);
            $a++;
        }
        $EndedBC = BCList::where('ended', true)->get();
        $a = 0;
        while ($a < count($EndedBC)){
            $EndedBC[$a]->GetUser;
            $EndedBC[$a]->GetType;
            $EndedBC[$a]->GetPatients;
            $EndedBC[$a]->GetPersonnel;
            $EndedBC[$a]->patients = count($EndedBC[$a]->GetPatients);
            $EndedBC[$a]->secouristes = count($EndedBC[$a]->GetPersonnel);
            $a++;
        }
        $puTypes = BCType::all();
        return response()->json([
            "status"=>'OK',
            'active'=>$ActiveBc,
            'ended'=>$EndedBC,
            'types'=>$puTypes,
        ]);
    }

    public function getBCState(int $id){
        // a faire
    }

    public function getBCByid(int $id){
        // a faire
    }

    public function addBc(Request $request){
        // a faire
    }

    public function endBc(int $id){
        //a faire
    }

    public function addPersonel(Request $request, int $id){
        // a faire
    }

    public function removePersonnel(int $id){
        // a faire
    }

    public function addPatient(Request $request, int $id){
        // a faire
    }

    public function removePatient(int $id, int $patient_id){
        // a faire
    }

}
