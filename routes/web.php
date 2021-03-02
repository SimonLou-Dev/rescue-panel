<?php

use App\Models\Rapport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::user()->grade == 0){
        return redirect()->route('logout');
    }
    return view("app");
})->middleware('auth');

Route::get('/patient/{a}', function (){
    if(Auth::user()->grade == 0){
        return redirect()->route('logout');
    }
   return view("app");
})->middleware('auth');

Route::get('/personnel/{a}', function (){
    if(Auth::user()->grade == 0){
        return redirect()->route('logout');
    }
    return view("app");
})->middleware('auth');

Route::get('/gestion/{a}', function (){
    if(Auth::user()->grade == 0){
        return redirect()->route('logout');
    }
    return view("app");
})->middleware('auth');

Route::get('/register',function (){
    return view("app");
})->name('register')->middleware('guest');

Route::get('/login',function (){
    return view("app");
})->name('login')->middleware('guest');

Route::get('/logout', function (){
   \Illuminate\Support\Facades\Auth::logout();
   return redirect()->route('login');
})->middleware('auth')->name('logout');


Route::post('/data/register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/data/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::get('/data/checkco', [\App\Http\Controllers\UserController::class, 'checkConnexion']);
Route::get('/data/getstatus', [\App\Http\Controllers\LayoutController::class, 'getservice']);
Route::get('/data/userisadmin', [\App\Http\Controllers\LayoutController::class, 'UserIsAdmin']);
Route::get('/data/setstatus/{state?}', [\App\Http\Controllers\LayoutController::class, 'setservice']);

Route::get('/data/rapport/getforinter', [\App\Http\Controllers\RapportController::class, 'getforinter']);
Route::post('data/rapport/post', [\App\Http\Controllers\RapportController::class, 'addRapport']);
Route::get('/data/patient/search/{text}', [\App\Http\Controllers\RapportController::class, 'search']);
Route::get('/data/patient/interlist/{text}', [\App\Http\Controllers\RapportController::class, 'getClient']);
Route::get('/data/rapport/inter/{id}', [\App\Http\Controllers\RapportController::class, 'getInter']);
Route::get('/data/rapport/get/{id}', [\App\Http\Controllers\RapportController::class, 'getRapportById']);
Route::put('/data/rapport/update/{id}', [\App\Http\Controllers\RapportController::class, 'updateRapport']);
Route::post('/data/rapport/changetel/{id}', [\App\Http\Controllers\RapportController::class, 'updatePatientTel']);

Route::get('/data/AllInService', [\App\Http\Controllers\MainController::class, 'getInServices']);
Route::get('/data/annonces', [\App\Http\Controllers\MainController::class, 'getAnnonces']);

Route::get('/data/pu/getstate', [\App\Http\Controllers\PuController::class, 'getInitialstate']);
Route::post('/data/pu/setstate/{activate}', [\App\Http\Controllers\PuController::class, 'setState']);
Route::get('/data/pu/getinfos/{activate}', [\App\Http\Controllers\PuController::class, 'getInfos']);

Route::get('/data/impaye/list', [\App\Http\Controllers\RapportController::class, 'getAllimpaye']);
Route::get('/data/impaye/paye/{id}', [\App\Http\Controllers\RapportController::class, 'paye']);

Route::get('/data/service/user', [\App\Http\Controllers\ServiceController::class, 'getUserService']);
Route::get('/data/service/alluser/{semaine?}', [\App\Http\Controllers\ServiceController::class, 'getAllservice']);
Route::get('/data/service/addwors', [\App\Http\Controllers\ServiceController::class, 'addRows']);

Route::get('/data/users/getall', [\App\Http\Controllers\UserController::class, 'getUser']);
Route::post('/data/users/setgrade/{id}/{userid}', [\App\Http\Controllers\UserController::class, 'setusergrade']);

Route::get('/data/pu/isInPu', [\App\Http\Controllers\PuController::class, 'isParticiping']);
Route::get('/data/pu/addtopu', [\App\Http\Controllers\PuController::class, 'addParticipant']);

Route::post('/data/pu/addpatient/{id}', [\App\Http\Controllers\PuController::class, 'addPatient']);
Route::delete('/data/pu/removepatient/{id}', [\App\Http\Controllers\PuController::class, 'deletePatient']);

Route::post('/data/gestion/content/add/{type}', [\App\Http\Controllers\ContentManagement::class, 'addcontent']);
Route::get('/data/gestion/content/get/{type}', [\App\Http\Controllers\ContentManagement::class, 'getcontent']);
Route::delete('/data/gestion/content/delete/{type}/{id}', [\App\Http\Controllers\ContentManagement::class, 'deletecontent']);
Route::get('/data/logs/{range}/{page}/{type}', [\App\Http\Controllers\ContentManagement::class, 'getLogs']);

Route::get('/data/gestion/service/setbyadmin/{userid}', [\App\Http\Controllers\ServiceController::class, 'setServiceByAdmin']);
Route::post('/data/impaye/addfacture', [\App\Http\Controllers\RapportController::class, 'addFacture']);

Route::get('/pdf/rapport/{id}', [\App\Http\Controllers\RapportController::class, 'makeRapportPdf']);
Route::get('/pdf/impaye/{from}/{to}', [\App\Http\Controllers\RapportController::class, 'makeImpayPdf']);


Route::get('/test', function (){

});

Route::get('/teste', function (){

    return view('pdf.rapport');
});
