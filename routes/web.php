<?php

use App\Enums\DiscordChannel;
use App\Events\NotifyForAll;
use App\Http\Controllers\Discord\DiscordChannelController;
use App\Http\Controllers\Users\AccountController;
use App\Http\Controllers\ContentManagement;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\request\PrimesController;
use App\Http\Controllers\Rapports\PoudreTestController;
use App\Http\Controllers\request\RemboursementsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\request\ModifierReqController;
use App\Http\Controllers\Service\ServiceGetterController;
use App\Http\Controllers\Service\ServiceSetterController;
use App\Http\Controllers\request\AbsencesController;
use App\Http\Controllers\VolController;
use App\Http\Controllers\BlackCodes\BCController;
use App\Http\Controllers\BlackCodes\BlesseController;
use App\Http\Controllers\BlackCodes\PersonnelController;
use App\Http\Controllers\Rapports\ExporterController;
use App\Http\Controllers\Rapports\FacturesController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Rapports\RapportController;
use App\Http\Controllers\Users\UserConnexionController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\UserGradeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use WebSocket\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

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
Route::get('/dashboard', [HomeController::class, 'getIndex'])->name('dashboard')->middleware(['auth']);
Route::get('/account', function (){return redirect()->route('dashboard');});
Route::get('/dispatch', function (){return redirect()->route('dashboard');});
Route::get('/patients/{a}/{b?}', function (){return redirect()->route('dashboard');});
Route::get('/blackcodes/{a}/{b?}', function (){return redirect()->route('dashboard');});
Route::get('/factures', function (){return redirect()->route('dashboard');});
Route::get('/logistique/{a?}', function (){return redirect()->route('dashboard');});
Route::get('/personnel/{a?}/{c?}', function (){return redirect()->route('dashboard');});
Route::get('/mdt/{a?}', function (){return redirect()->route('dashboard');});
Route::get('/SAMS/{a?}/{b?}', function (){return redirect()->route('dashboard');});
Route::get('/LSCoFD/{a?}/{b?}', function (){return redirect()->route('dashboard');});
Route::get('/cantaccess', function(){
    Session::forget('service');
    Session::forget('user');
    \Illuminate\Support\Facades\Auth::logout();
    \Illuminate\Support\Facades\Session::flush();
    Session::invalidate();
    Session::regenerateToken();
    $homme = new HomeController();
    return $homme->getIndex();
})->middleware(['auth'])->name('cantaccess');
Route::get('/servicenav', [HomeController::class, 'getIndex'])->name('servicenav');
Route::get('/', function(){
    return redirect()->route('dashboard');
});

Route::get('/data/bg', [LayoutController::class, 'getRandomBackGround'])->name('randomBg');

//Maintenance
Route::get('/maintenance/{a?}', [HomeController::class, 'getIndex'])->name('mnt');
//informations
Route::get('/informations/{a?}', [HomeController::class, 'getIndex'])->name('informations'); //->middleware('auth');
Route::get('/register/{a?}', [HomeController::class, 'getIndex'])->name('register')->middleware('guest');
Route::get('/login/{a?}', [HomeController::class, 'getIndex'])->name('login')->middleware('guest');
Route::get('/logout', function (Request $request){
   Session::forget('service');
   Session::forget('user');
    \Illuminate\Support\Facades\Auth::logout();
    \Illuminate\Support\Facades\Session::flush();
    Session::invalidate();
    Session::regenerateToken();
   return redirect()->route('login');
})->middleware('auth')->name('logout');




//Connexion management
Route::get('/auth/redirect', function () {return Socialite::driver('discord')->scopes(['email'])->redirect();});
Route::get('/auth/callback', [UserConnexionController::class, 'callback'])->middleware('guest');
Route::get('/data/userInfos', [UserGradeController::class, 'GetUserPerm'])->middleware(['auth']);

Route::post('/data/postuserinfos', [UserConnexionController::class, 'postInfos'])->middleware(['auth']);
Route::get('/data/check/connexion', [UserConnexionController::class, 'checkConnexion']);
//renommer la fonction
Route::put('/data/users/setCrossService/{id}', [UserController::class, 'setCrossService'])->middleware(['auth']);
Route::get('/data/patient/{patientId}/impaye', [PatientController::class, 'getImpaye'])->middleware(['auth']);

//Annonces & Utils & Actus
Route::get('/data/dashboard', [MainController::class, 'getDashboard']);
Route::post('/data/mgt/annonce', [MainController::class, 'createAnnonce']);
Route::post('/data/mgt/actu', [MainController::class, 'createActu']);
Route::put('/data/mgt/utils', [MainController::class, 'updateUtilsInfos']);
Route::get('/data/mgt/utils',[MainController::class, 'getUtilsInfos']);


//Rapport management
Route::get('/data/rapport/getforinter', [RapportController::class, 'getforinter'])->middleware(['auth']);
Route::post('/data/rapport/post', [RapportController::class, 'addRapport'])->middleware(['auth']);
Route::get('/data/patient/get/{id}', [PatientController::class, 'getPatient'])->middleware(['auth']);
Route::get('/data/rapport/get/{patientId}', [RapportController::class, 'getPatientInter'])->middleware(['auth']);
Route::put('/data/rapport/update/{id}', [RapportController::class, 'updateRapport'])->middleware(['auth']);
Route::put('/data/patient/update/{id}', [PatientController::class, 'updatePatientInfos'])->middleware(['auth']);
Route::get('/pdf/rapport/{id}', [ExporterController::class, 'makeRapportPdf'])->middleware(['auth']);
Route::get('/data/patient/getAll', [PatientController::class, 'getAllPatientsSearcher'])->middleware(['auth']);
//Tests de poudre

Route::post('/data/poudre/add', [PoudreTestController::class, 'postTest'])->middleware(['auth']);
Route::get('/data/poudre/get', [PoudreTestController::class, 'getAllTests'])->middleware(['auth']);
Route::get('/data/poudre/PDF/{id}', [PoudreTestController::class, 'exportTest'])->middleware(['auth']);

//LES BC
Route::get('/data/blackcode/load', [BCController::class, 'getMainPage'])->middleware(['auth']);
Route::get('/data/blackcode/{id}/infos', [BCController::class, 'getBCByid'])->middleware(['auth']);
Route::post('/data/blackcode/{id}/add/patient', [BlesseController::class, 'addPatient'])->middleware(['auth']);
Route::post('/data/blackcode/{BCId}/add/personnel/{userId}', [PersonnelController::class, 'addPersonel'])->middleware(['auth']);
Route::post('/data/blackcode/create', [BCController::class, 'addBc'])->middleware(['auth']);
Route::put('/data/blackcode/{id}/close', [BCController::class, 'endBc'])->middleware(['auth']);
Route::delete('/data/blackcode/delete/patient/{patient_id}', [BlesseController::class, 'removePatient'])->middleware(['auth']);
Route::delete('/data/blackcode/{id}/delete/personnel', [PersonnelController::class, 'removePersonnel'])->middleware(['auth']);
Route::get('/exel/allPList', [BlesseController::class, 'generateListWithAllPatients'])->middleware(['auth']);
Route::get('/data/bc/rapport/{id}', [BCController::class, 'generateRapport'])->middleware(['auth']);

Route::patch('/data/blackcode/{id}/caserne', [BCController::class, 'casernePatcher'])->middleware(['auth']);
Route::patch('/data/blackcode/{id}/desc', [BCController::class, 'descPatcher'])->middleware(['auth']);
Route::patch('/data/blackcode/{id}/infos', [BCController::class, 'infosPatcher'])->middleware(['auth']);
Route::patch('/data/blackcode/quit', [BCController::class, 'quitBc'])->middleware(['auth']);

//Les factures
Route::get('/data/facture/list', [FacturesController::class, 'getAllimpaye'])->middleware(['auth']);
Route::put('/data/facture/{id}/paye', [FacturesController::class, 'paye'])->middleware(['auth']);
Route::post('/data/facture/add', [FacturesController::class, 'addFacture'])->middleware(['auth']);
Route::get('/PDF/facture/{from}/{to}', [ExporterController::class, 'makeImpayPdf'])->middleware(['auth']);

//Service management
Route::patch('/data/service/user', [ServiceSetterController::class, 'setservice'])->middleware(['auth']);
Route::get('/data/service/user', [ServiceGetterController::class, 'getUserService'])->middleware(['auth']);
Route::get('/data/service/alluser/{semaine?}', [ServiceGetterController::class, 'getAllservice'])->middleware(['auth']);
Route::get('/data/service/addwors', [ServiceSetterController::class, 'addRows'])->middleware(['auth']);
Route::put('/data/service/setbyadmin/{userid}', [ServiceSetterController::class, 'setServiceByAdmin'])->middleware(['auth']);
Route::put('/data/service/admin/modify', [ServiceSetterController::class, 'modifyTimeService'])->middleware(['auth']);
Route::get('/data/service/admin/exel/{week?}', [ServiceGetterController::class, 'getWeekServiceExel'])->middleware(['auth']);
Route::get('/data/service/users',[ServiceGetterController::class, 'getUserOnServiceInUnit']);

//Time of Service modify Req
Route::get('/data/service/req/mylist', [ModifierReqController::class,'getMyModifyTimeServiceRequest'])->middleware(['auth']);
Route::post('/data/service/req/post', [ModifierReqController::class, 'postModifyTimeServiceRequest'])->middleware(['auth']);
Route::put('/data/service/req/accept/{id}', [ModifierReqController::class,'acceptModifyTimeServiceRequest'])->middleware(['auth']);
Route::put('/data/service/req/refuse/{id}', [ModifierReqController::class,'refuseModifyTimeServiceRequest'])->middleware(['auth']);
Route::get('/data/service/req/waitinglist', [ModifierReqController::class,'getAllModifyTimeServiceRequest'])->middleware(['auth']);

//Absence req
Route::get('/data/absence', [AbsencesController::class, 'getMyAbsences'])->middleware(['auth']);
Route::post('/data/absence', [AbsencesController::class, 'postMyReqAbsence'])->middleware(['auth']);
Route::delete('/data/absence/{id}', [AbsencesController::class, 'deleteAbsence'])->middleware(['auth']);
Route::put('/data/absence/accept/{id}', [AbsencesController::class, 'acceptReqAbsence'])->middleware(['auth']);
Route::put('/data/absence/refuse/{id}', [AbsencesController::class, 'refuseReqAbsence'])->middleware(['auth']);
Route::get('/data/admin/absence', [AbsencesController::class, 'getAbsences'])->middleware(['auth']);


// Primes Req
Route::get('/data/primes/getall', [PrimesController::class, 'gelAllReqPrimes'])->middleware(['auth']);
Route::get('/data/primes/getmy', [PrimesController::class, 'getMyReqPrimes'])->middleware(['auth']);
Route::post('/data/primes/post', [PrimesController::class, 'addReqPrimes'])->middleware(['auth']);
Route::put('/data/primes/accept/{id}',[PrimesController::class, 'acceptReqPrimes'])->middleware(['auth']);
Route::put('/data/primes/refuse/{id}', [PrimesController::class, 'refuseReqPrimes'])->middleware(['auth']);


//User management
Route::get('/data/users/getall', [UserController::class, 'getUser'])->middleware(['auth']);
Route::post('/data/users/setgrade/{id}/{userid}', [UserGradeController::class, 'setusergrade'])->middleware(['auth']);
Route::put('/data/users/pilote/{user_id}', [UserController::class, 'changePilote'])->middleware(['auth']);
Route::get('/data/user/{user_id}/sheet', [UserController::class, 'getSheet'])->middleware(['auth']);
Route::post('/data/usersheet/{user_id}/note', [UserController::class, 'addUserNote'])->middleware(['auth']);
Route::post('/data/usersheet/{user_id}/sanctions', [UserController::class, 'addUserSanction'])->middleware(['auth']);

Route::put('/data/usersheet/{user_id}/material', [UserController::class, 'ModifyUserMaterial'])->middleware(['auth']);
Route::put('/data/usersheet/{user_id}/quitService', [UserController::class, 'userQuitService'])->middleware(['auth']);
Route::get('/data/users/export', [UserController::class, 'exportListPersonnelExel'])->middleware(['auth']);

//Content management
Route::post('/data/gestion/content/{type}', [ContentManagement::class, 'addcontent'])->middleware(['auth']);
Route::get('/data/gestion/content', [ContentManagement::class, 'getcontent'])->middleware(['auth']);
Route::delete('/data/gestion/content/{type}/{id}', [ContentManagement::class, 'deletecontent'])->middleware(['auth']);

//Carnet de vol
Route::get('/data/vols', [VolController::class, 'getVolsList'])->middleware(['auth']);
Route::post('/data/vols', [VolController::class, 'addVol'])->middleware(['auth']);

//Recap
Route::get('/data/remboursements/me', [RemboursementsController::class, 'getRemboursementOfUser'])->middleware(['auth']);
Route::post('/data/remboursements/post', [RemboursementsController::class, 'addRemboursement'])->middleware(['auth']);
Route::get('/data/remboursements/all', [RemboursementsController::class, 'gelAllReqremboursement'])->middleware(['auth']);
Route::patch('/data/remboursements/accept/{rmbId}', [RemboursementsController::class, 'acceptRemboursement'])->middleware(['auth']);
Route::patch('/data/remboursements/refuse/{rmbId}', [RemboursementsController::class, 'refuseRemboursement'])->middleware(['auth']);

//Acount infos
Route::get('/data/user/infos/get', [UserController::class, 'getUserInfos'])->middleware(['auth']);
Route::put('/data/user/infos/put', [AccountController::class, 'updateInfos'])->middleware(['auth']);

//Pems management
Route::get('/data/admin/grades', [UserGradeController::class, 'getGrade'])->middleware(['auth']);
Route::post('/data/admin/grades', [UserGradeController::class, 'createGrade'])->middleware(['auth']);
Route::put('/data/admin/grades', [UserGradeController::class, 'updateGrade'])->middleware(['auth']);
Route::delete('/data/admin/grades', [UserGradeController::class, 'deleteGrade'])->middleware(['auth']);

//Pems management
Route::get('/data/management/discord', [DiscordChannelController::class, 'getChannel'])->middleware(['auth']);
Route::put('/data/management/discord', [DiscordChannelController::class, 'postChannel'])->middleware(['auth']);

//Change service
Route::patch('/data/user/service/{service}', [UserController::class, 'setService'])->middleware(['auth']);

//Logs getting
Route::get('/data/user/logs',[ContentManagement::class, 'getLogs'])->middleware(['auth']);



//Infos utils
Route::get('/data/infosutils/get', [MainController::class, 'getUtilsInfos'])->middleware(['auth']);
Route::put('/data/infosutils/put', [MainController::class, 'updateUtilsInfos'])->middleware(['auth']);


Route::any('/tunnel', [ErrorsController::class, 'tunelSentry']);
//Searching
Route::get('/data/patient/search/{text}', [SearchController::class, 'searchPatient'])->middleware(['auth']);
Route::get('/data/users/search/{user}', [SearchController::class, 'searchUser'])->middleware(['auth']);


if(env('APP_DEBUG') === true || env('APP_DEBUG') === "true"){
    Route::get('/serch', function (Request $request){
        Discord::chanUpdate(DiscordChannel::RI, 933706570552999946);
        Discord::chanUpdate(DiscordChannel::Facture, 933706570552999946);
        Discord::chanUpdate(DiscordChannel::BC, 933706570552999946);
        Discord::chanUpdate(DiscordChannel::Service, 933706570552999946);
        Discord::chanUpdate(DiscordChannel::MedicInfos, 923521332531048469);
        Discord::chanUpdate(DiscordChannel::FireInfos,934029889122762773);
        Discord::chanUpdate(DiscordChannel::FireRemboursement, 934029889122762773);
        Discord::chanUpdate(DiscordChannel::MedicRemboursement, 923521332531048469);
        Discord::chanUpdate(DiscordChannel::Absences, 933706570552999946);
        Discord::chanUpdate(DiscordChannel::Bugs, 933706570552999946);
        Discord::chanUpdate(DiscordChannel::FireSanctions, 934029889122762773);
        Discord::chanUpdate(DiscordChannel::MedicSanctions, 923521332531048469);
        Discord::chanUpdate(DiscordChannel::FireLogistique, 934029889122762773);
        Discord::chanUpdate(DiscordChannel::MedicLogistique, 923521332531048469);
    })->middleware('web');

    Route::get('/auth/test', function (Request $req){
        return response()->json(['name'=>\App\Models\User::where('id',1)->first()]);
    });

    Route::get('/auth/fake', [UserConnexionController::class, 'fake'])->middleware('guest');//disable
}


