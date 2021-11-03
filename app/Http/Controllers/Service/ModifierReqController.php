<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModifierReqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function postModifyTimeServiceRequest(request $request)
    {
        //create request
    }

    public function acceptModifyTimeServiceRequest(string $id)
    {
        //accept request
    }

    public function refuseModifyTimeServiceRequest(string $id)
    {
        //refuse request
    }

    public function getAllwaitingModifyTimeServiceRequest()
    {
        //Get all waiting request
    }

    public function getMyModifyTimeServiceRequest()
    {
        //Get my requests
    }
}
