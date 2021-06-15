<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function setservice(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        ServiceController::setService($user, false);
        $text = "";
        if($user->service){
            $text = 'Vous êtes en service !';
        }else{
            $text = 'Vous n\'êtes plus en service';
        }
        event(new Notify($text,2));
        return response()->json([
            'status'=>'OK',
            'user'=>$user,
        ]);
    }

    public function getservice(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        return response()->json(['service'=>$user->service]);
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
