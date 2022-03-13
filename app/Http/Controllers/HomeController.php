<?php

namespace App\Http\Controllers;


use App\Models\LogDb;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @param string|null $a
     * @return Application|Factory|View
     */
    public function getIndex(Request $request){
        $value = $request->session()->get('error', '');
        $request->session()->forget('error');
        return view("home",['errors'=> $value]);
    }

    public function getLogs(){
        $user = User::where('id',\Auth::user()->id)->first();
        if($user->dev){
            $logs = LogDb::orderBy('id','desc')->paginate();
        }else{
            $logs = LogDb::where('action','!=','authentifications')->orderBy('id','desc')->paginate();
        }
        return response()->json(['logs'=>$logs]);
    }
}
