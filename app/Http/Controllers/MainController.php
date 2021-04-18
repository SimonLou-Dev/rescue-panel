<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Annonces;
use App\Models\User;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{

    public function getInServices(Request $request): \Illuminate\Http\JsonResponse
    {
        $userInServie = User::where('service', true)->orderByDesc('id')->get();
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


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postBug(Request $request): \Illuminate\Http\JsonResponse
    {
        Http::post(env('WEBHOOK_BUGS'),[
            'embeds'=>[
                [
                    'title'=>'Nouveau BUG :',
                    'color'=>'1285790',
                    'description'=>$request->text,
                    'footer'=>[
                        'text' => 'Signalé par : ' . Auth::user()->name,
                    ]
                ]
            ]
        ]);
        return response()->json([],201);
    }


}
