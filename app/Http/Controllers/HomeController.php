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
     * @return View|Factory|Application
     */
    public function getIndex(): View|Factory|Application
    {
        $value = \Illuminate\Support\Facades\Session::get('error', '');
        \Illuminate\Support\Facades\Session::forget('error');
        return view("home",['errors'=> $value]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id',\Auth::user()->id)->first();
        if($user->dev){
            $logs = LogDb::orderBy('id','desc')->paginate();
        }else{
            $logs = LogDb::where('action','!=','authentifications')->orderBy('id','desc')->paginate();
        }
        return response()->json(['logs'=>$logs]);
    }
}
