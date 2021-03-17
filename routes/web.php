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
//Main view
Route::get('/', function () {
    if(Auth::user()->grade_id == 0){
        return redirect()->route('ANA');
    }
    return view("app");
})->middleware('auth');
//View in patient
Route::get('/patient/{a}', function (){
    if(Auth::user()->grade_id == 0){
        return redirect()->route('ANA');
    }
   return view("app");
})->middleware('auth');
//View of personnel
Route::get('/personnel/{a}', function (){
    if(Auth::user()->grade_id == 0){
        return redirect()->route('ANA');
    }
    return view("app");
})->middleware('auth');
//View of gestion
Route::get('/gestion/{a}', function (){
    if(Auth::user()->grade_id == 0){
        return redirect()->route('ANA');
    }
    return view("app");
})->middleware('auth');
// Report bug
Route::get('/bugrepport', function (){
    if(Auth::user()->grade_id == 0){
        return redirect()->route('ANA');
    }
    return view("app");
})->middleware('auth');
//Cant access
Route::get('/ANA', function (){
    return view("app");
})->name('ANA');
//Maintenance
Route::get('/maintenance', function (){
    return view("app");
})->name('mnt');
//informations
Route::get('/informations', function (){
    return view("app");
})->middleware('auth');
//register
Route::get('/register',function (){
    return view("app");
})->name('register')->middleware('guest');
//login
Route::get('/login',function (){
    return view("app");
})->name('login')->middleware('guest');
//log out
Route::get('/logout', function (){
   \Illuminate\Support\Facades\Auth::logout();
   return redirect()->route('login');
})->middleware('auth')->name('logout');
//reset mdp view
Route::get('/reset/?', function (){
    return view("app");
})->middleware('guest');
//send mail for reseset
Route::get('/sendmail', function (){
    return view("app");
})->middleware('guest');

//Connexion management
Route::post('/data/register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/data/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/data/postuserinfos', [\App\Http\Controllers\UserController::class, 'postInfos']);
Route::get('/data/check/connexion', [\App\Http\Controllers\UserController::class, 'checkConnexion']);
Route::get('/data/getstatus', [\App\Http\Controllers\LayoutController::class, 'getservice']);
Route::get('/data/getperm', [\App\Http\Controllers\UserController::class, 'GetUserPerm']); //renommer la fonction
Route::put('/data/setstatus', [\App\Http\Controllers\LayoutController::class, 'setservice']);
Route::get('/data/annonces', [\App\Http\Controllers\MainController::class, 'getAnnonces']);
//Route::post('/data/check/maintenance')

//Rapport management
Route::get('/data/rapport/getforinter', [\App\Http\Controllers\RapportController::class, 'getforinter']);
Route::post('/data/rapport/post', [\App\Http\Controllers\RapportController::class, 'addRapport']);
Route::get('/data/patient/search/{text}', [\App\Http\Controllers\RapportController::class, 'search']);
Route::get('/data/patient/interlist/{text}', [\App\Http\Controllers\RapportController::class, 'getClient']);
// DELETED Route::get('/data/rapport/inter/{id}', [\App\Http\Controllers\RapportController::class, 'getInter']);
Route::get('/data/rapport/get/{id}', [\App\Http\Controllers\RapportController::class, 'getRapportById']);
Route::put('/data/rapport/update/{id}', [\App\Http\Controllers\RapportController::class, 'updateRapport']);
Route::post('/data/patient/{id]/update', [\App\Http\Controllers\RapportController::class, 'updatePatientInfos']);
Route::get('/PDF/rapport/{id}', [\App\Http\Controllers\RapportController::class, 'makeRapportPdf']);

//LES BC

//Les factures
Route::get('/data/facture/list', [\App\Http\Controllers\RapportController::class, 'getAllimpaye']);
Route::put('/data/facture/{id}/paye', [\App\Http\Controllers\RapportController::class, 'paye']);
Route::post('/data/facture/add', [\App\Http\Controllers\RapportController::class, 'addFacture']);
Route::get('/PDF/facture/{from}/{to}', [\App\Http\Controllers\RapportController::class, 'makeImpayPdf']);

//Service management
Route::get('/data/service/user', [\App\Http\Controllers\ServiceController::class, 'getUserService']);
Route::get('/data/service/alluser/{semaine?}', [\App\Http\Controllers\ServiceController::class, 'getAllservice']);
Route::get('/data/service/addwors', [\App\Http\Controllers\ServiceController::class, 'addRows']);
Route::get('/data/AllInService', [\App\Http\Controllers\MainController::class, 'getInServices']);
Route::put('/data/service/setbyadmin/{userid}', [\App\Http\Controllers\ServiceController::class, 'setServiceByAdmin']);

//User management
Route::get('/data/users/getall', [\App\Http\Controllers\UserController::class, 'getUser']);
Route::post('/data/users/setgrade/{id}/{userid}', [\App\Http\Controllers\UserController::class, 'setusergrade']);

//Content management
Route::post('/data/gestion/content/add/{type}', [\App\Http\Controllers\ContentManagement::class, 'addcontent']);
Route::get('/data/gestion/content/get/{type}', [\App\Http\Controllers\ContentManagement::class, 'getcontent']);
Route::delete('/data/gestion/content/delete/{type}/{id}', [\App\Http\Controllers\ContentManagement::class, 'deletecontent']);
Route::get('/data/logs/{range}/{page}/{type}', [\App\Http\Controllers\ContentManagement::class, 'getLogs']);


Route::get('/test', function (){
    return event(new \App\Events\Notify('test',1));
});

Route::get('/two', function (){
    return view('login');
});



