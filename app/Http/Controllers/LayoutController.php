<?php

namespace App\Http\Controllers;

use App\Models\DayService;
use App\Models\Services;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LayoutController extends Controller
{
    public function setservice(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = \App\Models\User::where('id', Auth::id())->first();
        ServiceController::setService($user, false);
        return response()->json([
            'status'=>'OK',
            'user'=>$user,
        ]);
    }

    public function getservice(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['service'=>Auth::user()->service]);
    }



    public static function getdaystring(): string
    {
        $string = null;
        switch (date('D', time())){
            case "Mon":
                $string = 'lundi';
                break;
            case 'Tue':
                $string = 'mardi';
                break;
            case 'Wed':
                $string = 'mercredi';
                break;
            case 'Thu':
                $string = 'jeudi';
                break;
            case 'Fri':
                $string = 'vendredi';
                break;
            case 'Sat':
                $string = 'samedi';
                break;
            case 'Sun':
                $string = 'dimanche';
                break;
            default:
                $string = null;
                break;
        }
        return $string;
    }
}
