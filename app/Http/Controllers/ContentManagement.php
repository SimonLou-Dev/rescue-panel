<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Http\Controllers\Service\ModifierReqController;
use App\Models\Annonces;
use App\Models\BCList;
use App\Models\BCType;
use App\Models\Blessure;
use App\Models\CouleurVetement;
use App\Models\Facture;
use App\Models\Hospital;
use App\Models\Intervention;
use App\Models\LieuxSurvol;
use App\Models\LogServiceState;
use App\Models\ModifyServiceReq;
use App\Models\ObjRemboursement;
use App\Models\Pathology;
use App\Models\PrimeItem;
use App\Models\Rapport;
use App\Models\Service;
use App\Models\ServiceState;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class ContentManagement extends Controller
{

    public function addcontent(Request $request, string $type): \Illuminate\Http\JsonResponse
    {
        $this->authorize('modify_content_mgt',User::class);
        switch ($type) {
            case "1":
                $content = new Intervention();
                $content->name = $request->name;
                $content->service = Session::get('service')[0];
                $content->save();
                break;
            case "2":
                $content = new Hospital();
                $content->name = $request->name;
                $content->service = Session::get('service')[0];
                $content->save();
                break;
            case "3":
                $content = new BCType();
                $content->name = $request->name;
                $content->save();
                break;
            case "4":
                $content = new Blessure();
                $content->name = $request->name;
                $content->service = Session::get('service')[0];
                $content->save();
                break;
            case "5":
                $content = new CouleurVetement();
                $content->name = $request->name;
                $content->service = Session::get('service')[0];
                $content->save();
                break;
            case "6" :
                $content = new LieuxSurvol();
                $content->name= $request->name;
                $content->save();
                break;
            case '7':
                $content =new Pathology();
                $content->desc = $request->desc;
                $content->name= $request->name;
                $content->stock_item= json_encode([]);
                $content->save();
                break;
            default:
                return response()->json('error', 404);
        }
        return self::getcontent($request);
    }
    public function getcontent(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('modify_content_mgt',User::class);
        return response()->json([
            'interventions'=> Intervention::where('service', Session::get('service')[0])->get(),
            'hospital'=>Hospital::where('service', Session::get('service')[0])->get(),
            'BCTypes'=>BCType::all(),
            'Blessures'=>Blessure::where('service', Session::get('service')[0])->get(),
            'Color'=>CouleurVetement::where('service', Session::get('service')[0])->get(),
            'LieuxSurvol'=>LieuxSurvol::all(),
            'Pathologies'=>Pathology::all(),
        ]);

    }
    public function deletecontent(Request $request, int $type, int $id): \Illuminate\Http\JsonResponse
    {
        $this->authorize('modify_content_mgt',User::class);
        switch ($type){
            case "1":Intervention::where('id', $id)->first()->delete();
                break;
            case "2":
                Hospital::where('id', $id)->first()->delete();
                break;
            case "3":
                BCType::where('id', $id)->first()->delete();
                break;
            case "4";Blessure::where('id', $id)->first()->delete();
                break;
            case "5";
                CouleurVetement::where('id', $id)->first()->delete();
                break;
            case "6";
                LieuxSurvol::where('id', $id)->first()->delete();
                break;
            case '7':
                Pathology::where('id',$id)->first()->delete();
                break;
            default: break;
        }
        Notify::broadcast('Supprésion réussie', 1, Auth::user()->id);
        return self::getcontent($request);
    }

    public function getLogs(string $range,string $page, string $type): \Illuminate\Http\JsonResponse
    {
        $datas = null; $pages = null; $row = null;
        $range = (int) $range;
        $page = (int) $page -1;
       switch ($type){
           case "1":
               $counter = count(Rapport::all());
               $datas = Rapport::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil(($counter) / $range));
               $row = $counter;
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->GetPatient;
                   $datas[$a]->GetType;
                   $datas[$a]->GetFacture;
                   $a++;
               }
               break;
           case "2":
               $counter = count(Service::all());
               $datas = Service::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil(($counter) / $range));
               $row = $counter;
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->getUser;
                   $a++;
               }
               break;
           case "3":
               $counter = count(Facture::all());
               $datas = Facture::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil(($counter) / $range));
               $row = $counter;
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->GetPatient;
                   $a++;
               }

               break;
           case "4":
               $counter = count(BCList::all());
               $datas = BCList::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil(($counter) / $range));
               $row = $counter;
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->GetUser;
                   $a++;
               }
               break;
           case "5":
               $counter = count(LogServiceState::all());
               $datas = LogServiceState::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil(($counter) / $range));
               $row = $counter;

               foreach ($datas as $data){
                   $data->GetUser;
                   $data->GetState;
               }
               break;
           case "6":
               $counter = count(ModifyServiceReq::all());
               $datas = ModifyServiceReq::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil(($counter) / $range));
               $row = $counter;
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->GetUser;
                   $datas[$a]->GetAdmin;
                   $a++;
               }
               break;
           default: break;

       }
        return response()->json(['status'=>'OK', 'datas'=>$datas, 'pages'=>$pages, 'lignes'=>$row]);
    }
}
