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
    public function setservice(Request $request){
        $user = \App\Models\User::where('id', Auth::id())->first();
        ServiceController::setService($user, false);
        return response()->json([
            'status'=>'OK',
            'user'=>$user,
        ]);
    }
    public function getservice(Request $request){
        return response()->json(['service'=>Auth::user()->OnService]);
    }
    public function UserIsAdmin(Request $request){
        $user = \App\Models\User::where('id', Auth::id())->first();
        if(Auth::user()->grade >= 5){
            return response()->json(['status'=>'OK', 'IsAdmin'=>true]);
        }else{
            return response()->json(['status'=>'OK', 'IsAdmin'=>false]);
        }
    }


    public static function getdaystring(): string
    {
        switch (date('D', time())){
            case "Mon":
                return 'lundi';
                break;
            case 'Tue':
                return 'mardi';
                break;
            case 'Wed':
                return 'mercredi';
                break;
            case 'Thu':
                return 'jeudi';
                break;
            case 'Fri':
                return 'vendredi';
                break;
            case 'Sat':
                return 'samedi';
                break;
            case 'Sun':
                return 'dimanche';
                break;
            default:
                return '';
                break;
        }
    }
}
