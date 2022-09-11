<?php

namespace App\Http\Controllers\Api;

use App\Models\Qr;
use App\Models\QrDesign;
use App\Models\QrPublic;
use App\Models\QrVisits;
use Illuminate\Http\Request;
use App\Models\QrInformation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PublicTemplateController extends Controller
{
    public function show(Request $request)
    {

        // $data = Qr::where('uuid_public', $request->uuid)->first();
        // $data = Qr::with('qr_designs')

        //qr_designs,

        $data1 = Qr::where('uuid_public', $request->uuid)->first();

        $data2 = QrInformation::where('qr_id', $data1->id)->first();

        $data3 = QrDesign::where('qr_id', $data1->id)->first();


        $resultado = ["data1" => $data1, "data2" => $data2, "data3" => $data3];


        if ($data1) {
            return response()->json(['data' => $resultado, 'message' => 'Success'], 200);
        }
        return response()->json(['error' => 404, 'message' => 'Not found'], 404);
    }

    public function addVisita(Request $request)
    {
        DB::beginTransaction();
        try {
            $resData = QrVisits::where('uuid', $request->uuid)->increment("visit");
            DB::commit();
            return response()->json(['data' => $resData, 'status' => 'Success'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 404, 'message' => 'Not found'], 404);
        }
    }
}
