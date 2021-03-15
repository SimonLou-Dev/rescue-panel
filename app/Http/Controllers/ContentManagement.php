<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Annonces;
use App\Models\BlessuresTypes;
use App\Models\Factures;
use App\Models\HospitalList;
use App\Models\InterType;
use App\Models\PatientsVetement;
use App\Models\PlanUrgence;
use App\Models\PUTypes;
use App\Models\Rapport;
use App\Models\Services;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ContentManagement extends Controller
{
    public function addcontent(Request $request, string $type): \Illuminate\Http\JsonResponse
    {
        switch ($type) {
            case "1":
                $content = new InterType();
                $content->name = $request->formcontent;
                $content->save();
                return response()->json(['status'=>'OK', 'created'=>$content], 201);
            case "2":
                $content = new HospitalList();
                $content->name = $request->formcontent;
                $content->save();
                return response()->json(['status'=>'OK', 'created'=>$content], 201);
                break;
            case "3":
                $content = new PUTypes();
                $content->name = $request->formcontent;
                $content->save();
                return response()->json(['status'=>'OK', 'created'=>$content], 201);
                break;
            case "4";
                $content = new BlessuresTypes();
                $content->name = $request->formcontent;
                $content->save();
                return response()->json(['status'=>'OK', 'created'=>$content], 201);
                break;
            case "5";
                $content = new Annonces();
                $content->title = $request->title;
                $content->content = $request->formcontent;
                $content->posted_at = date('Y-m-d H:i:s', time());
                $content->save();
                Http::post(env('WEBHOOK_ANNONCE'),[
                    'embeds'=>[
                        [
                            'title'=>'Nouvelle annonce : ' . $content->title,
                            'color'=>'10359636',
                            'description'=> $content->content,
                        ]
                    ]
                ]);
                return response()->json(['status'=>'OK', 'created'=>$content], 201);
                break;
            case 6;
                $content = new PatientsVetement();
                $content->name = $request->formcontent;
                $content->save();
                return response()->json(['status'=>'OK', 'created'=>$content], 201);
                break;
            default:
                return response()->json('error', 404);
                break;
        }
    }
    public function getcontent(Request $request, int $type): \Illuminate\Http\JsonResponse
    {
        $data= array();
        switch ($type){
            case "1":
                $data = InterType::all();
                break;
            case "2":
                $data = HospitalList::all();
                break;
            case "3":
                $data = PUTypes::all();
                break;
            case "4":
                $data = BlessuresTypes::all();
                break;
            case "5":
                $data = Annonce::all();
                break;
            case "6":
                $data = PatientsVetement::all();
                break;
            default: break;
        }
        return response()->json(['status'=>'OK', 'data'=>$data]);
    }
    public function deletecontent(Request $request, int $type, int $id): \Illuminate\Http\JsonResponse
    {
        $data = null;
        switch ($type){
            case "1":
                $data = InterType::where('id', $id)->first();
                $data->delete();
                break;
            case "2":
                $data = HospitalList::where('id', $id)->first();
                $data->delete();
                break;
            case "3":
                $data = PUTypes::where('id', $id)->first();
                $data->delete();
                break;
            case "4";
                $data = BlessuresTypes::where('id', $id)->first();
                $data->delete();
                break;
            case "5";
                $data = Annonce::where('id', $id)->first();
                $data->delete();
                break;
            case "6";
                $data = PatientsVetement::where('id', $id)->first();
                $data->delete();
                break;
            default: break;
        }
        return response()->json(['status'=>'OK'], 204);

    }

    public function getLogs($range,$page,$type){
        $range = (int) $range;
        $page = (int) $page -1;
       switch ($type){
           case "1":
               $datas = Rapport::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil((Rapport::all()->count()) / $range));
               $row = Rapport::all()->count();
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->Inter;
                   $datas[$a]->Patient;
                   $datas[$a]->facture;
                   $a++;
               }
               break;
           case "2":
               $datas = Services::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil((Services::all()->count()) / $range));
               $row = Services::all()->count();
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->getUser;
                   $a++;
               }
               break;
           case "3":
               $datas = Factures::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil((Factures::all()->count()) / $range));
               $row = Factures::all()->count();
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->patient;
                   $a++;
               }

               break;
           case "4":
               $datas = PlanUrgence::orderByDesc('id')->skip($range * $page)->take($range)->get();
               $pages = intval(ceil((PlanUrgence::all()->count()) / $range));
               $row = PlanUrgence::all()->count();
               $a = 0;
               while($a < count($datas)){
                   $datas[$a]->user;
                   $a++;
               }
               break;
           default: break;

       }
        return response()->json(['status'=>'OK', 'datas'=>$datas, 'pages'=>$pages, 'lignes'=>$row]);
    }
}
