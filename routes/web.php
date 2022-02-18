<?php

use App\Enums\DiscordChannel;
use App\Events\NotifyForAll;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContentManagement;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PrimesController;
use App\Http\Controllers\Rapports\PoudreTestController;
use App\Http\Controllers\RemboursementsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Service\ModifierReqController;
use App\Http\Controllers\Service\ServiceGetterController;
use App\Http\Controllers\Service\ServiceSetterController;
use App\Http\Controllers\Users\CredentialController;
use App\Http\Controllers\VolController;
use App\Http\Controllers\BlackCodes\BCController;
use App\Http\Controllers\BlackCodes\BlesseController;
use App\Http\Controllers\BlackCodes\PersonnelController;
use App\Http\Controllers\Discord\DiscordApiController;
use App\Http\Controllers\Formations\AdminController;
use App\Http\Controllers\Formations\CertificationController;
use App\Http\Controllers\Formations\FormationController;
use App\Http\Controllers\Formations\ResponseController;
use App\Http\Controllers\Rapports\ExporterController;
use App\Http\Controllers\Rapports\FacturesController;
use App\Http\Controllers\Rapports\PatientController;
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
Route::get("/infos", function (){
    return phpinfo();
});



//Main view
Route::get('/dashboard', [HomeController::class, 'getIndex'])->name('dashboard'); //->middleware(['auth']);
Route::get('/account', [HomeController::class, 'getIndex']); //->middleware(['auth']);
Route::get('/dispatch', [HomeController::class, 'getIndex']); //->middleware(['auth']);
Route::get('/patients/{a}/{b?}', [HomeController::class, 'getIndex']); //->middleware(['auth']);
Route::get('/blackcodes/{a}/{b?}', [HomeController::class, 'getIndex']); //->middleware(['auth']);
Route::get('/factures', [HomeController::class, 'getIndex']); //->middleware(['auth']);
Route::get('/formation/{a}/{b?}', [HomeController::class, 'getIndex']); //->middleware('auth');
Route::get('/logistique/{a?}', [HomeController::class, 'getIndex']); //->middleware('auth');
Route::get('/personnel/{a?}', [HomeController::class, 'getIndex']); //->middleware('auth');
Route::get('/mdt/{a?}', [HomeController::class, 'getIndex']); //->middleware('auth');
Route::get('/cantaccess', [HomeController::class, 'getIndex'])->name('cantaccess');
Route::get('/servicenav', [HomeController::class, 'getIndex'])->name('servicenav');
Route::get('/', function(){
    return redirect()->route('dashboard');
});

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
// scope=identify%20email%20guilds%20guilds.join%20guilds.members.read
Route::get('/auth/redirect', function () {return Socialite::driver('discord')->scopes(['email','guilds'])->redirect();});
Route::get('/auth/callback', [UserConnexionController::class, 'callback']);
Route::get('/auth/fake', [UserConnexionController::class, 'fake']);
Route::get('/data/userInfos', [UserGradeController::class, 'GetUserPerm']);

Route::post('/data/postuserinfos', [UserConnexionController::class, 'postInfos']);
Route::get('/data/check/connexion', [UserConnexionController::class, 'checkConnexion']);
Route::get('/data/getstatus', [LayoutController::class, 'getservice']);
//renommer la fonction
Route::get('/data/annonces', [MainController::class, 'getAnnonces']);
Route::put('/data/users/setdiscordId/{discordid}/{id}', [UserController::class, 'setDiscordId']);
//Route::post('/data/check/maintenance')
Route::get('/data/user/reset/send/{mail?}',  [CredentialController::class, 'sendResetMail']);
Route::get('/pass/reset/token/{uuid}',[CredentialController::class,'tokenVerify']);
Route::post('/data/user/reset/post',[CredentialController::class,'changepass'] );

Route::get('/data/patient/{patientId}/impaye', [PatientController::class, 'getImpaye']);

//Rapport management
Route::get('/data/rapport/getforinter', [RapportController::class, 'getforinter']);
Route::post('/data/rapport/post', [RapportController::class, 'addRapport']);
Route::get('/data/patient/get/{id}', [PatientController::class, 'getPatient']);
Route::get('/data/rapport/get/{patientId}', [RapportController::class, 'getPatientInter']);
Route::put('/data/rapport/update/{id}', [RapportController::class, 'updateRapport']);
Route::put('/data/patient/update/{id}', [PatientController::class, 'updatePatientInfos']);
Route::get('/pdf/rapport/{id}', [ExporterController::class, 'makeRapportPdf']);
Route::get('/data/patient/getAll', [PatientController::class, 'getAllPatientsSearcher']);
//Tests de poudre

Route::post('/data/poudre/add', [PoudreTestController::class, 'postTest']);
Route::get('/data/poudre/get', [PoudreTestController::class, 'getAllTests']);
Route::get('/data/poudre/PDF/{id}', [PoudreTestController::class, 'exportTest']);

//LES BC
Route::get('/data/blackcode/load', [BCController::class, 'getMainPage']);
Route::get('/data/blackcode/{id}/infos', [BCController::class, 'getBCByid']);
Route::get('/data/blackcode/{id}/status', [BCController::class, 'getBCState']);
Route::post('/data/blackcode/{id}/add/patient', [BlesseController::class, 'addPatient']);
Route::post('/data/blackcode/{BCId}/add/personnel/{userId}', [PersonnelController::class, 'addPersonel']);
Route::post('/data/blackcode/create', [BCController::class, 'addBc']);
Route::put('/data/blackcode/{id}/close', [BCController::class, 'endBc']);
Route::delete('/data/blackcode/delete/patient/{patient_id}', [BlesseController::class, 'removePatient']);
Route::delete('/data/blackcode/{id}/delete/personnel', [PersonnelController::class, 'removePersonnel']);
Route::get('/exel/allPList/{from}/{to}', [BlesseController::class, 'generateListWithAllPatients']);
Route::get('/data/bc/rapport/{id}', [BCController::class, 'generateRapport']);
Route::patch('/data/blackcode/{id}/caserne', [BCController::class, 'casernePatcher']);
Route::patch('/data/blackcode/{id}/desc', [BCController::class, 'descPatcher']);
Route::patch('/data/blackcode/{id}/infos', [BCController::class, 'infosPatcher']);

//Les factures
Route::get('/data/facture/list', [FacturesController::class, 'getAllimpaye']);
Route::put('/data/facture/{id}/paye', [FacturesController::class, 'paye']);
Route::post('/data/facture/add', [FacturesController::class, 'addFacture']);
Route::get('/PDF/facture/{from}/{to}', [ExporterController::class, 'makeImpayPdf']);

//Service management
Route::patch('/data/service/user', [ServiceSetterController::class, 'setservice']);
Route::get('/data/service/user', [ServiceGetterController::class, 'getUserService']);
Route::get('/data/service/alluser/{semaine?}', [ServiceGetterController::class, 'getAllservice']);
Route::get('/data/service/addwors', [ServiceSetterController::class, 'addRows']);
Route::get('/data/AllInService', [MainController::class, 'getInServices']);
Route::put('/data/service/setbyadmin/{userid}', [ServiceSetterController::class, 'setServiceByAdmin']);
Route::put('/data/service/admin/modify', [ServiceSetterController::class, 'modifyTimeService']);
Route::get('/data/service/admin/exel/{week?}', [ServiceGetterController::class, 'getWeekServiceExel']);

Route::get('/data/service/req/mylist', [ModifierReqController::class,'getMyModifyTimeServiceRequest']);
Route::post('/data/service/req/post', [ModifierReqController::class, 'postModifyTimeServiceRequest']);
Route::put('/data/service/req/accept/{id}', [ModifierReqController::class,'acceptModifyTimeServiceRequest']);
Route::put('/data/service/req/refuse/{id}', [ModifierReqController::class,'refuseModifyTimeServiceRequest']);
Route::get('/data/service/req/waitinglist', [ModifierReqController::class,'getAllModifyTimeServiceRequest']);

//User management
Route::get('/data/users/getall', [UserController::class, 'getUser']);
Route::post('/data/users/setgrade/{id}/{userid}', [UserGradeController::class, 'setusergrade']);
Route::put('/data/users/pilote/{user_id}', [UserController::class, 'changePilote']);
Route::put('/data/user/{user_id}/changestate/{state}', [UserController::class, 'changeState']);
Route::get('/data/usersheet/{user_id}/note', [UserController::class, 'getUserNote']);
Route::get('/data/usersheet/{user_id}/sanctions', [UserController::class, 'getUserSanctions']);
Route::get('/data/usersheet/{user_id}/infos', [UserController::class, 'getUserInfos']);
Route::get('/data/usersheet/{user_id}/material', [UserController::class, 'getUserMaterial']);
Route::post('/data/usersheet/{user_id}/note', [UserController::class, 'addUserNote']);
Route::delete('/data/usersheet/{user_id}/{note_id}/note', [UserController::class, 'removeUserNote']);
Route::post('/data/usersheet/{user_id}/sanctions', [UserController::class, 'addUserSanction']);
Route::put('/data/usersheet/{user_id}/material', [UserController::class, 'ModifyUserMaterial']);
Route::put('/data/usersheet/{user_id}/quitService', [UserController::class, 'userQuitService']);
Route::get('/data/users/export', [UserController::class, 'exportListPersonnelExel']);

//Content management
Route::post('/data/gestion/content/add/{type}', [ContentManagement::class, 'addcontent']);
Route::get('/data/gestion/content/get/{type}', [ContentManagement::class, 'getcontent']);
Route::delete('/data/gestion/content/delete/{type}/{id}', [ContentManagement::class, 'deletecontent']);
Route::get('/data/logs/{range}/{page}/{type}', [ContentManagement::class, 'getLogs']);

//Carnet de vol
Route::get('/data/vol/get/{page}/{name?}', [VolController::class, 'getVolsList']);
Route::post('/data/vol/add', [VolController::class, 'addVol']);


//Formation
Route::get('/data/certifications/admin/get', [CertificationController::class, 'getUsersCertifications']);
Route::put('/data/certifications/admin/{forma_id}/change/{user_id}', [CertificationController::class, 'changeUserCertification']);
Route::get('/data/formations/admin/{formation_id}/get', [AdminController::class, 'getFormationByIdAdmin']);
Route::put('/data/formations/admin/{formation_id}/visibylity', [AdminController::class, 'changeFormationVisibility']);
Route::post('/data/formations/admin/post', [AdminController::class, 'postFormation']);
Route::put('/data/formations/admin/{formation_id}/update', [AdminController::class, 'updateFormation']);
Route::delete('/data/formations/admin/{formation_id}/delete', [AdminController::class, 'deleteFormationById']);
Route::post('/data/formations/{formation_id}/admin/question/post', [AdminController::class, 'addQuestion']);
Route::put('/data/formations/admin/question/{question_id}/update', [AdminController::class, 'updateQuestion']);
Route::delete('/data/formations/admin/question/{question_id}/delete', [AdminController::class, 'deleteQuestion']);
Route::get('/data/formations/get/{page?}/{max?}', [FormationController::class, 'getFormations']);
Route::get('/data/formations/{formation_id}/get', [FormationController::class, 'getFormationById']);
Route::get('/data/formations/question/{question_id}', [FormationController::class, 'getQuestionById']);
Route::post('/data/formations/response/{question_id}/save', [ResponseController::class, 'saveResponseState']);
Route::get('/data/formations/userdeco/{formation_id}', [FormationController::class, 'userDisconnect']);
Route::get('/data/formation/{formation_id}/final', [FormationController::class, 'getFinalDatas']);
Route::post('/data/formations/question/{question_id}/image', [AdminController::class, 'postQuestionImage']);
Route::post('/data/formations/{formation_id}/image', [AdminController::class, 'postFormationsImage']);
Route::get('/data/formations/{formation_id}/responses', [ResponseController::class, 'getReponseOffFormations']);
Route::delete('/data/formations/responses/{response_id}/delete', [AdminController::class, 'deleteResponseByID']);

//Recap
Route::get('/data/remboursements/get', [RemboursementsController::class, 'getRemboursementOfUser']);
Route::get('/data/remboursements/get/admin/{weeknumber?}', [RemboursementsController::class, 'getRemboursementByWeek']);
Route::post('/data/remboursements/post', [RemboursementsController::class, 'addRemboursement']);
Route::delete('/data/remboursements/delete/{itemid}', [RemboursementsController::class, 'deleteRemboursement']);

//Acount infos
Route::get('/data/user/infos/get', [UserController::class, 'getUserInfos']);
Route::put('/data/user/infos/put', [AccountController::class, 'updateInfos']);
Route::put('/data/user/mdp/put', [AccountController::class, 'changeMdp']);
Route::post('/data/user/bg/post', [AccountController::class, 'addBgImg']);
Route::delete('/data/user/bg/delete', [AccountController::class, 'deleteBgImg']);

//Pems management
Route::get('/data/admin/grades/get', [UserGradeController::class, 'getAllGrades']);
Route::put('/data/admin/grades/{perm}/{grade_id}', [UserGradeController::class, 'changePerm']);

// Primes
Route::get('/data/primes/getall', [PrimesController::class, 'gelAllReqPrimes']);
Route::get('/data/primes/getmy', [PrimesController::class, 'getMyReqPrimes']);
Route::post('/data/primes/post', [PrimesController::class, 'addReqPrimes']);
Route::put('/data/primes/accept/{id}',[PrimesController::class, 'acceptReqPrimes']);
Route::put('/data/primes/refuse/{id}', [PrimesController::class, 'refuseReqPrimes']);


//Temp upload
Route::post('/data/tempupload', [FileController::class, 'uploadFile'])->middleware('auth');
Route::put('/data/finish/tempupload/{uuid}', [FileController::class, 'endOffUpload'])->middleware('auth');
Route::delete('/data/delete/tempupload', [FileController::class, 'deleteTempFile'])->middleware('auth');

//Infos utils
Route::get('/data/infosutils/get', [MainController::class, 'getUtilsInfos']);
Route::put('/data/infosutils/put', [MainController::class, 'updateUtilsInfos']);

//Errors & bug reporter
Route::post('/data/front/errors', [ErrorsController::class, 'frontErrors']);
Route::any('/tunnel', [ErrorsController::class, 'tunelSentry']);
Route::post('/data/bug', [MainController::class, 'postBug']);

//Searching
Route::get('/data/patient/search/{text}', [SearchController::class, 'searchPatient']);
Route::get('/data/vol/searsh/{pilote?}', [VolController::class, 'seatchPilote']);
Route::get('/data/users/search/{user}', [SearchController::class, 'searchUser']);

Route::get('/test', function (){

   $test = event(new \App\Events\Notify('test',1, Auth::user()->id));
   dd($test);
});

Route::get('/teste', function (Request $request){
    NotifyForAll::broadcast('Début du BC # à ', 3);
})->middleware('web');

Route::get('/serch', function (Request $request){
    Discord::chanUpdate(DiscordChannel::RI, 933706570552999946);
    Discord::chanUpdate(DiscordChannel::Facture, 933706570552999946);
    Discord::chanUpdate(DiscordChannel::BC, 933706570552999946);
    Discord::chanUpdate(DiscordChannel::Service, 933706570552999946);
})->middleware('web');





/*
Auth with token

Http::withHeader([
    'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
])->get('https://discord.com/api/v9/users/@me/guilds')->body();



*/


//Post message https://discord.com/api/v9/channels/{chan id}/messages => content comme les embeds
    //=> response id (id msg), channel_id

//Patch msg https://discord.com/api/v9/channels/{chan}/messages/{id} => content comme les messages
    //=> response id (id msg), channel_id

//delete msg  https://discord.com/api/v9/channels/{channel.id}/messages/{message.id}

//get msg https://discord.com/api/v9/channels/923521378248974347/messages/926904661238251561
    // si reaction reactions => en array

//Post reaction https://discord.com/api/v9/channels/{channel.id}/messages/{message.id}/reactions/{emoji}/@me
//Emoji in url encoded


