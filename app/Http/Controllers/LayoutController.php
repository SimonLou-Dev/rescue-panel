<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Http\Controllers\Service\OperatorController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LayoutController extends Controller
{


    public function getRandomBackGround(){
        $ext = '.png';
        $service = '';
        

        return response()->json(['image'=> '/storage/background/0.png');


    }

    public static function getdaystring(int $a = null): string
    {
        if(is_null($a))$a = time();
        $string = null;
        switch (date('D', $a)){
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
