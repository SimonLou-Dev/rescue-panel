<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function getInServices(Request $request): \Illuminate\Http\JsonResponse
    {
        $userInServie = User::where('OnService', true)->get();
        return response()->json(['status'=>'OK', 'users'=>$userInServie]);
    }
    public function getAnnonces(Request $request): \Illuminate\Http\JsonResponse
    {
        $annonces = Annonce::orderByDesc('id')->get();
        return response()->json(['status'=>'OK', 'annonces'=>$annonces]);
    }
}
