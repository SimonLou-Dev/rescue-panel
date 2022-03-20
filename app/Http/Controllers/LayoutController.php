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
        $error = '0';
        $selected = 'gle';

        if(Auth::user() && isset(\Session::get('service')[0])){
            $service = \Session::get('service')[0] . '_';
        }

        $bg = [
            $service . '0',
            $service . '1',
            $service . '2',
            $service . '3',
            $service . '4',
            $service . '5',
            $service . '6',
            $service . '7',
            $service . '8',
            $service . '9',
        ];
        $a = 0;
        while (!\File::exists('storage/background/'.$selected) && $a < 15){
            $selected = $bg[random_int(0,9)] . '.png';
            $a++;
        }

        if($a == 15){
            $selected = $error. '.png';
        }
   
        return response()->json(['image'=> '/storage/background/'.$selected]);


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
