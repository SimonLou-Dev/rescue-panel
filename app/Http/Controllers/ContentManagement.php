<?php

namespace App\Http\Controllers;

use App\Models\Annonces;
use App\Models\BCList;
use App\Models\BCType;
use App\Models\Blessure;
use App\Models\CouleurVetement;
use App\Models\Facture;
use App\Models\Hospital;
use App\Models\Intervention;
use App\Models\LieuxSurvol;
use App\Models\ObjRemboursement;
use App\Models\Rapport;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ContentManagement extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function addcontent(Request $request, string $type): \Illuminate\Http\JsonResponse
    {
        /**
 * @var string $request->formcontent 
*/
        $formcontent = $request->formcontent;

        switch ($type) {
        case "1":
            $content = new Intervention();

            $content->name = $request->formcontent;
            $content->save();
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        case "2":
            $content = new Hospital();
            /**
 * @var string $request->formcontent  
*/
            $content->name = $request->formcontent;
            $content->save();
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        case "3":
            $content = new BCType();
            /**
 * @var string $request->formcontent  
*/
            $content->name = $request->formcontent;
            $content->save();
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        case "4":
            $content = new Blessure();
            /**
 * @var string $request->formcontent  
*/
            $content->name = $request->formcontent;
            $content->save();
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        case "5":
            $content = new Annonces();
            /**
 * @var string $request->title  
*/
            $content->title = $request->title;
            /**
 * @var string $request->formcontent  
*/
            $content->content = $request->formcontent;
            $content->save();
            Http::post(
                env('WEBHOOK_ANNONCE'), [
                'embeds'=>[
                    [
                        'title'=>'Nouvelle annonce : ' . $content->title,
                        'color'=>'10359636',
                        'description'=> $content->content,
                    ]
                ]
                    ]
            );
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        case "6":
            $content = new CouleurVetement();
            /**
 * @var string $request->formcontent  
*/
            $content->name = $request->formcontent;
            $content->save();
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        case "7" :
            $content = new LieuxSurvol();
            $content->name= $request->formcontent;
            $content->save();
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        case '8':
            $content =new ObjRemboursement();
            $content->price = $request->price;
            $content->name= $request->formcontent;
            $content->save();
            return response()->json(['status'=>'OK', 'created'=>$content], 201);
        default:
            return response()->json('error', 404);
        }
    }
    public function getcontent(Request $request, int $type): \Illuminate\Http\JsonResponse
    {
        $data= array();
        switch ($type){
        case "1":
            $data = Intervention::all();
            break;
        case "2":
            $data = Hospital::all();
            break;
        case "3":
            $data = BCType::all();
            break;
        case "4":
            $data = Blessure::all();
            break;
        case "5":
            $data = Annonces::all();
            break;
        case "6":
            $data = CouleurVetement::all();
            break;
        case "7":
            $data = LieuxSurvol::all();
            break;
        case "8":
            $data = ObjRemboursement::all();
            break;
        default: 
            break;
        }
        return response()->json(['status'=>'OK', 'data'=>$data]);
    }
    public function deletecontent(Request $request, int $type, int $id): \Illuminate\Http\JsonResponse
    {
        $data = null;
        switch ($type){
        case "1":
            $data = Intervention::where('id', $id)->first();
            $data->delete();
            break;
        case "2":
            $data = Hospital::where('id', $id)->first();
            $data->delete();
            break;
        case "3":
            $data = BCType::where('id', $id)->first();
            $data->delete();
            break;
        case "4";
            $data = Blessure::where('id', $id)->first();
            $data->delete();
            break;
        case "5";
            $data = Annonces::where('id', $id)->first();
            $data->delete();
            break;
        case "6";
            $data = CouleurVetement::where('id', $id)->first();
            $data->delete();
            break;
        default: 
            break;
        }
        return response()->json(['status'=>'OK'], 204);

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
        default: 
            break;

        }
        return response()->json(['status'=>'OK', 'datas'=>$datas, 'pages'=>$pages, 'lignes'=>$row]);
    }
}
