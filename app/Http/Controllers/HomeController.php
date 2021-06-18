<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @param string|null $a
     * @return Application|Factory|View
     */
    public function getIndex(string $a =null){
        return view("home");
    }
}
