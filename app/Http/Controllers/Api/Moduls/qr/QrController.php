<?php

namespace App\Http\Controllers\Api\Moduls\qr;

use App\Models\Qr;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QrDesign;
use App\Models\QrInformation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qrs = Qr::orderBy('id','desc')->get();
        return response()->json(['data' => $qrs,'message' => 'Success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre_proyecto' => 'required',
                'video_url' => 'required',
                'embed_code' => 'required',
                // 'qr_imagen_medio' => 'required|mimes:jpg,png,jpeg',
                // 'image_welcome' => 'required|mimes:jpg,png,jpeg',
            ]
        );

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "Validation error", "errors" => $validator->errors()]);
        }

        DB::beginTransaction();
        try {

            $qr = new Qr;
            $qr->uuid = Uuid::generate()->string;
            $qr->name = $request->nombre_proyecto;
            $qr->url_video = $request->video_url;
            $qr->video_description = $request->video_descipcion;
            $qr->is_active = 1;
            $qr->user_id = auth()->id();
            $qr->save();

            if ($request->hasFile('image_welcome')) {
                $file = $request->file('image_welcome');
                $name = time() . $file->getClientOriginalName();
                $filePath = 'images/' . $name;
                Storage::disk('s3')->put($filePath, file_get_contents($file));
            }else{
                $filePath = "";
            }

            $qr_information = new QrInformation;
            $qr_information->background_panel = $request->background_color;
            $qr_information->business = $request->empresa_nombre;
            $qr_information->video_title = $request->empresa_titulo;
            $qr_information->description = $request->empresa_descripcion;
            $qr_information->link_fb = $request->red_fb ? $request->empresa_red_fb : "" ;
            $qr_information->link_tw = $request->red_tw ? $request->empresa_red_tw : "" ;
            $qr_information->link_tk = $request->red_tik ? $request->empresa_red_tik : "" ;
            $qr_information->welcome_screen = $filePath;
            $qr_information->qr_id = $qr->id;
            $qr_information->save();


            if ($request->hasFile('qr_imagen_medio')) {
                $file_medio = $request->file('qr_imagen_medio');
                $name_medio = time() . $file_medio->getClientOriginalName();
                $filePath_medio = 'images/' . $name_medio;
                Storage::disk('s3')->put($filePath_medio, file_get_contents($file_medio));
            }else{
                $filePath_medio = "";
            }

            $qr_desing = new QrDesign;
            $qr_desing->url_address = $request->qr_url_direccion;
            $qr_desing->dots_style = $request->qr_modelo_qr;
            $qr_desing->dots_color = $request->qr_color_model;
            $qr_desing->corners_style = $request->qr_modelo_bolita;
            $qr_desing->corners_color = $request->qr_color_modelo_bolita;
            $qr_desing->background_color = $request->qr_color_fondo;
            $qr_desing->image_file_center = $filePath_medio;
            $qr_desing->qr_id = $qr->id;
            $qr_desing->save();

            DB::commit();
            $response["status"] = "successs";
            $response["message"] = "Data guardado con éxito..!";
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response["status"] = "failed";
            $response["message"] = "Failed! image(s) not uploaded";
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
