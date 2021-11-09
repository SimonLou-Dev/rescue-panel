<?php

namespace App\Http\Controllers\Formations;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Formation;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    /**
     * @param string $forma_id
     * @param string $user_id
     * @return JsonResponse
     */
    public function changeUserCertification(string $forma_id, string $user_id): JsonResponse
    {
        $forma_id = (int) $forma_id;
        $user_id = (int) $user_id;
        $certif = Certification::where('formation_id', $forma_id);
        if($certif->count() == 0){
            $certif = new Certification();
            $certif->user_id = $user_id;
            $certif->formation_id = $forma_id;
            $certif->save();
        }else{
            $certif->first()->delete();
        }
        event(new Notify('La certification a été ' . $certif->count()  == 0? 'ajoutée':'supprimée',1));
        return \response()->json(['status'=>'OK'],200);
    }

    /**
     * @return JsonResponse
     */
    public function getUsersCertifications(): JsonResponse
    {
        $forma = Formation::all();
        $grades = Grade::where('perm_0', true)->get();
        $list = array();
        foreach ($grades as $grade){
            array_push($list, $grade->id);
        }
        $users = User::whereIn('grade_id', $list)->orderBy('id', 'Asc')->get();
        foreach ($users as $user){
            $user->GetCertifications;
        }
        return response()->json([
            'status'=>'OK',
            'users'=>$users,
            'certifs'=>$forma,
            'nbrForma'=>count($forma),
        ]);
    }
}
