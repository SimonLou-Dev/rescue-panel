<?php

use App\Events\Notify;
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
Route::get('/', function () {return view("app");})->middleware(['auth','access']);
//View in patient
Route::get('/patient/{a}', function (){return view("app");})->middleware(['auth','access']);
//View of personnel
Route::get('/personnel/{a}', function (){return view("app");})->middleware(['auth','access']);
//View of gestion
Route::get('/gestion/{a}', function (){return view("app");})->middleware('auth');
// Report bug
Route::get('/bugrepport', function (){return view("app");})->middleware('auth');
//Cant access
Route::get('/ANA', function (){return view("app");})->name('ANA');
//Maintenance
Route::get('/maintenance', function (){return view("app");})->name('mnt');
//informations
Route::get('/informations', function (){return view("app");})->middleware(['auth']);
//register
Route::get('/register',function (){return view("app");})->name('register')->middleware('guest');
//login
Route::get('/login',function (){return view("app");})->name('login')->middleware('guest');
//log out
Route::get('/logout', function (){
   \Illuminate\Support\Facades\Auth::logout();
   \Illuminate\Support\Facades\Session::flush();
   return redirect()->route('login');
})->middleware('auth')->name('logout');
//reset mdp view
Route::get('/reset/*', function (){return view("app");})->middleware('guest');
//send mail for reseset
Route::get('/sendmail', function (){return view("app");})->middleware('guest');

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
Route::get('/data/patient/interlist/{text}', [\App\Http\Controllers\RapportController::class, 'getPatient']);
// DELETED Route::get('/data/rapport/inter/{id}', [\App\Http\Controllers\RapportController::class, 'getInter']);
Route::get('/data/rapport/get/{id}', [\App\Http\Controllers\RapportController::class, 'getRapportById']);
Route::put('/data/rapport/update/{id}', [\App\Http\Controllers\RapportController::class, 'updateRapport']);
Route::post('/data/patient/{id]/update', [\App\Http\Controllers\RapportController::class, 'updatePatientInfos']);
Route::get('/PDF/rapport/{id}', [\App\Http\Controllers\RapportController::class, 'makeRapportPdf']);

//LES BC
Route::get('/data/blackcode/load', [\App\Http\Controllers\BCController::class, 'getMainPage']);
Route::get('/data/blackcode/mystatus', [\App\Http\Controllers\BCController::class, 'getUserInfos']);
Route::get('/data/blackcode/{id}/infos', [\App\Http\Controllers\BCController::class, 'getBCByid']);
Route::get('/data/blackcode/{id}/status', [\App\Http\Controllers\BCController::class, 'getBCState']);
Route::post('/data/blackcode/{id}/add/patient', [\App\Http\Controllers\BCController::class, 'addPatient']);
Route::post('/data/blackcode/{id}/add/personnel', [\App\Http\Controllers\BCController::class, 'addPersonel']);
Route::post('/data/blackcode/create', [\App\Http\Controllers\BCController::class, 'addBc']);
Route::put('/data/blackcode/{id}/close', [\App\Http\Controllers\BCController::class, 'endBc']);
Route::delete('/data/blackcode/delete/patient/{patient_id}', [\App\Http\Controllers\BCController::class, 'removePatient']);
Route::delete('/data/blackcode/{id}/delete/personnel', [\App\Http\Controllers\BCController::class, 'removePersonnel']);

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

//Carnet de vol
Route::get('/data/vol/get/{page}/{name?}', [\App\Http\Controllers\VolController::class, 'getVolsList']);
Route::post('/data/vol/add', [\App\Http\Controllers\VolController::class, 'addVol']);
Route::get('/data/vol/searsh/{pilote}', [\App\Http\Controllers\VolController::class, 'seatchPilote']);

//Formation
Route::get('/data/certifications/admin/get', [\App\Http\Controllers\FormationController::class, 'getUsersCertifications']);
Route::put('/data/certifications/admin/{certif_id}/change', [\App\Http\Controllers\FormationController::class, 'changeUserCertification']);
Route::get('/data/formations/admin/{formation_id}/get', [\App\Http\Controllers\FormationController::class, 'getFormationByIdAdmin']);
Route::put('/data/formations/admin/{formation_id}/visibylity', [\App\Http\Controllers\FormationController::class, 'changeFormationVisibility']);
Route::post('/data/formations/admin/post', [\App\Http\Controllers\FormationController::class, 'postFormation']);
Route::put('/data/formations/admin/{formation_id}/update', [\App\Http\Controllers\FormationController::class, 'updateFormation']);
Route::delete('/data/formations/admin/{formation_id}/delete', [\App\Http\Controllers\FormationController::class, 'deleteFormationById']);
Route::post('/data/formations/{formation_id}/admin/question/post', [\App\Http\Controllers\FormationController::class, 'addQuestion']);
Route::put('/data/formations/admin/question/{question_id}/update', [\App\Http\Controllers\FormationController::class, 'updateQuestion']);
Route::delete('/data/formations/admin/question/{question_id}/delete', [\App\Http\Controllers\FormationController::class, 'deleteQuestion']);
Route::get('/data/formations/get', [\App\Http\Controllers\FormationController::class, 'getFormations']);
Route::get('/data/formations/{formation_id}/get', [\App\Http\Controllers\FormationController::class, 'getFormationById']);
Route::get('/data/formations/question/{question_id}', [\App\Http\Controllers\FormationController::class, 'getQuestionById']);
Route::post('/data/formations/response/{question_id}/save', [\App\Http\Controllers\FormationController::class, 'saveResponseState']);
Route::delete('/data/formation/response/{question_id}/delete', [\App\Http\Controllers\FormationController::class, 'deleteResponseStateById']);
Route::get('/data/formation/{formation_id}/final', [\App\Http\Controllers\FormationController::class, 'getFinalDatas']);

//Recap
Route::get('/data/remboursements/get', [\App\Http\Controllers\RemboursementsController::class, 'getRemboursementOfUser']);
Route::get('/data/remboursements/get/admin/{weeknumber?}', [\App\Http\Controllers\RemboursementsController::class, 'getRemboursementByWeek']);
Route::post('/data/remboursements/post', [\App\Http\Controllers\RemboursementsController::class, 'addRemboursement']);
Route::delete('/data/remboursements/delete/{itemid}', [\App\Http\Controllers\RemboursementsController::class, 'deleteRemboursement']);




