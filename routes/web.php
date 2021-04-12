<?php


use App\Http\Controllers\AccountController;
use App\Http\Controllers\BCController;
use App\Http\Controllers\ContentManagement;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\RemboursementsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolController;
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
Route::get('/', [HomeController::class, 'getIndex'])->middleware(['auth','access']);
//View in patient
Route::get('/patient/{a}', [HomeController::class, 'getIndex']); //->middleware(['auth','access']);
//View of personnel
Route::get('/personnel/{a}', [HomeController::class, 'getIndex']); //->middleware(['auth','access']);
//View of gestion
Route::get('/gestion/{a}', [HomeController::class, 'getIndex']); //->middleware('auth');
// Report bug
Route::get('/bugrepport/{a?}', [HomeController::class, 'getIndex']); //->middleware('auth');
//Cant access
Route::get('/ANA/{a?}', [HomeController::class, 'getIndex'])->name('ANA');
//Maintenance
Route::get('/maintenance/{a?}', [HomeController::class, 'getIndex'])->name('mnt');
//informations
Route::get('/informations/{a?}', [HomeController::class, 'getIndex']); //->middleware(['auth']);
//register
Route::get('/register/{a?}', [HomeController::class, 'getIndex'])->name('register'); //->middleware('guest');
//login
Route::get('/login/{a?}', [HomeController::class, 'getIndex'])->name('login'); //->middleware('guest');
//log out
Route::get('/logout', function (){
   \Illuminate\Support\Facades\Auth::logout();
   \Illuminate\Support\Facades\Session::flush();
   return redirect()->route('login');
})->middleware('auth')->name('logout');
//reset mdp view
Route::get('/reset/*', [HomeController::class, 'getIndex']); //->middleware('guest');
//send mail for reseset
Route::get('/sendmail', [HomeController::class, 'getIndex'])->middleware('guest');

//Connexion management
Route::post('/data/register', [UserController::class, 'register']);
Route::post('/data/login', [UserController::class, 'login']);
Route::post('/data/postuserinfos', [UserController::class, 'postInfos']);
Route::get('/data/check/connexion', [UserController::class, 'checkConnexion']);
Route::get('/data/getstatus', [LayoutController::class, 'getservice']);
Route::get('/data/getperm', [UserController::class, 'GetUserPerm']); //renommer la fonction
Route::put('/data/setstatus', [LayoutController::class, 'setservice']);
Route::get('/data/annonces', [MainController::class, 'getAnnonces']);
//Route::post('/data/check/maintenance')

//Rapport management
Route::get('/data/rapport/getforinter', [RapportController::class, 'getforinter']);
Route::post('/data/rapport/post', [RapportController::class, 'addRapport']);
Route::get('/data/patient/search/{text}', [RapportController::class, 'search']);
Route::get('/data/patient/interlist/{text}', [RapportController::class, 'getPatient']);
// DELETED Route::get('/data/rapport/inter/{id}', [\A [RapportController::class, 'getInter']);
Route::get('/data/rapport/get/{id}', [RapportController::class, 'getRapportById']);
Route::put('/data/rapport/update/{id}', [RapportController::class, 'updateRapport']);
Route::post('/data/patient/{id]/update', [RapportController::class, 'updatePatientInfos']);
Route::get('/PDF/rapport/{id}', [RapportController::class, 'makeRapportPdf']);

//LES BC
Route::get('/data/blackcode/load', [BCController::class, 'getMainPage']);
Route::get('/data/blackcode/mystatus', [BCController::class, 'getUserInfos']);
Route::get('/data/blackcode/{id}/infos', [BCController::class, 'getBCByid']);
Route::get('/data/blackcode/{id}/status', [BCController::class, 'getBCState']);
Route::post('/data/blackcode/{id}/add/patient', [BCController::class, 'addPatient']);
Route::post('/data/blackcode/{id}/add/personnel', [BCController::class, 'addPersonel']);
Route::post('/data/blackcode/create', [BCController::class, 'addBc']);
Route::put('/data/blackcode/{id}/close', [BCController::class, 'endBc']);
Route::delete('/data/blackcode/delete/patient/{patient_id}', [BCController::class, 'removePatient']);
Route::delete('/data/blackcode/{id}/delete/personnel', [BCController::class, 'removePersonnel']);

//Les factures
Route::get('/data/facture/list', [RapportController::class, 'getAllimpaye']);
Route::put('/data/facture/{id}/paye', [RapportController::class, 'paye']);
Route::post('/data/facture/add', [RapportController::class, 'addFacture']);
Route::get('/PDF/facture/{from}/{to}', [RapportController::class, 'makeImpayPdf']);

//Service management
Route::get('/data/service/user', [ServiceController::class, 'getUserService']);
Route::get('/data/service/alluser/{semaine?}', [ServiceController::class, 'getAllservice']);
Route::get('/data/service/addwors', [ServiceController::class, 'addRows']);
Route::get('/data/AllInService', [MainController::class, 'getInServices']);
Route::put('/data/service/setbyadmin/{userid}', [ServiceController::class, 'setServiceByAdmin']);
Route::put('/data/service/admin/modify', [ServiceController::class, 'modifyTimeService']);
Route::get('/data/service/admin/exel/{week?}', [ServiceController::class, 'getWeekServiceExel']);

//User management
Route::get('/data/users/getall', [UserController::class, 'getUser']);
Route::post('/data/users/setgrade/{id}/{userid}', [UserController::class, 'setusergrade']);
Route::get('/data/users/search/{user}', [UserController::class, 'searchUser']);
Route::put('/data/users/pilote/{user_id}', [UserController::class, 'changePilote']);

//Content management
Route::post('/data/gestion/content/add/{type}', [ContentManagement::class, 'addcontent']);
Route::get('/data/gestion/content/get/{type}', [ContentManagement::class, 'getcontent']);
Route::delete('/data/gestion/content/delete/{type}/{id}', [ContentManagement::class, 'deletecontent']);
Route::get('/data/logs/{range}/{page}/{type}', [ContentManagement::class, 'getLogs']);

//Carnet de vol
Route::get('/data/vol/get/{page}/{name?}', [VolController::class, 'getVolsList']);
Route::post('/data/vol/add', [VolController::class, 'addVol']);
Route::get('/data/vol/searsh/{pilote?}', [VolController::class, 'seatchPilote']);

//Formation
Route::get('/data/certifications/admin/get', [FormationController::class, 'getUsersCertifications']);
Route::put('/data/certifications/admin/{forma_id}/change/{user_id}', [FormationController::class, 'changeUserCertification']);
Route::get('/data/formations/admin/{formation_id}/get', [FormationController::class, 'getFormationByIdAdmin']);
Route::put('/data/formations/admin/{formation_id}/visibylity', [FormationController::class, 'changeFormationVisibility']);
Route::post('/data/formations/admin/post', [FormationController::class, 'postFormation']);
Route::put('/data/formations/admin/{formation_id}/update', [FormationController::class, 'updateFormation']);
Route::delete('/data/formations/admin/{formation_id}/delete', [FormationController::class, 'deleteFormationById']);
Route::post('/data/formations/{formation_id}/admin/question/post', [FormationController::class, 'addQuestion']);
Route::put('/data/formations/admin/question/{question_id}/update', [FormationController::class, 'updateQuestion']);
Route::delete('/data/formations/admin/question/{question_id}/delete', [FormationController::class, 'deleteQuestion']);
Route::get('/data/formations/get/{page?}/{max?}', [FormationController::class, 'getFormations']);
Route::get('/data/formations/{formation_id}/get', [FormationController::class, 'getFormationById']);
Route::get('/data/formations/question/{question_id}', [FormationController::class, 'getQuestionById']);
Route::post('/data/formations/response/{question_id}/save', [FormationController::class, 'saveResponseState']);
Route::get('/data/formations/userdeco/{formation_id}', [FormationController::class, 'userDisconnect']);
Route::get('/data/formation/{formation_id}/final', [FormationController::class, 'getFinalDatas']);

//Recap
Route::get('/data/remboursements/get', [RemboursementsController::class, 'getRemboursementOfUser']);
Route::get('/data/remboursements/get/admin/{weeknumber?}', [RemboursementsController::class, 'getRemboursementByWeek']);
Route::post('/data/remboursements/post', [RemboursementsController::class, 'addRemboursement']);
Route::delete('/data/remboursements/delete/{itemid}', [RemboursementsController::class, 'deleteRemboursement']);

//Acount infos
Route::get('/data/user/infos/get', [AccountController::class, 'getInfos']);
Route::put('/data/user/infos/put', [AccountController::class, 'updateInfos']);
Route::put('/data/user/mdp/put', [AccountController::class, 'changeMdp']);
Route::post('/data/user/bg/post', [AccountController::class, 'addBgImg']);
Route::delete('/data/user/bg/delete', [AccountController::class, 'deleteBgImg']);

//Pems management
Route::get('/data/admin/grades/get', [UserController::class, 'getAllGrades']);
Route::put('/data/admin/grades/{perm}/{grade_id}', [UserController::class, 'changePerm']);

Route::post('/data/bug', [MainController::class, 'postBug']);


Route::post('/data/tempupload', [FileController::class, 'uploadFile'])->middleware('auth');
Route::put('/data/finish/tempupload/{uuid}', [FileController::class, 'endOffUpload'])->middleware('auth');
Route::delete('/data/delete/tempupload', [FileController::class, 'deleteTempFile'])->middleware('auth');


