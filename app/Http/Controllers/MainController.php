<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Annonces;
use App\Models\User;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function getInServices(Request $request): \Illuminate\Http\JsonResponse
    {
        $userInServie = User::where('service', true)->get();
        return response()->json(['status'=>'OK', 'users'=>$userInServie]);
    }
    public function getAnnonces(Request $request): \Illuminate\Http\JsonResponse
    {
        $annonces = Annonces::orderByDesc('id')->get();
        foreach ($annonces as $annonce){
            $annonce->content = Markdown::convertToHtml($annonce->content);
        }
        return response()->json(['status'=>'OK', 'annonces'=>$annonces]);
    }
}
