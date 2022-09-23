<?php

namespace App\Http\Controllers\Api\Moduls\qr;

use App\Models\Qr;
use App\Models\QrDesign;
use App\Models\QrPublic;
use App\Models\QrVisits;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\QrInformation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class QrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qrs = Qr::with(['QrDesign' => function ($res1) {
            $res1->select('*');
        }, 'QrVisits' => function ($res2) {
            $res2->select('qr_id', 'visit');
        }])->orderBy('id', 'desc')->get();

        return response()->json(['data' => $qrs, 'message' => 'Success'], 200);
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
                'uuid_public' => 'required',
                'uuid_visit' => 'required'
                // 'qr_imagen_medio' => 'required|mimes:jpg,png,jpeg',
                // 'image_welcome' => 'required|mimes:jpg,png,jpeg',
            ]
        );

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "Validation error", "errors" => $validator->errors()]);
        }

        DB::beginTransaction();
        try {

            //$uuid_public = Uuid::generate(4);
            $qr = new Qr;
            $qr->uuid = Uuid::generate()->string;
            $qr->name = $request->nombre_proyecto;
            $qr->url_video = $request->video_url;
            $qr->embed_code = $request->embed_code;
            $qr->video_description = $request->video_descipcion;
            $qr->uuid_public = $request->uuid_public;
            $qr->uuid_visit = $request->uuid_visit;
            $qr->is_active = 1;
            $qr->user_id = auth()->id();
            $qr->save();

            if ($request->hasFile('image_welcome')) {
                $file = $request->file('image_welcome');
                $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'imagesqr']);
                $info_public_id = $obj->getPublicId();
                $info_url = $obj->getSecurePath();
                // $file = $request->file('image_welcome');
                // $name = time() . $file->getClientOriginalName();
                // $filePath = 'images/' . $name;
                // Storage::disk('s3')->put($filePath, file_get_contents($file));
            } else {
                $info_public_id = "";
                $info_url = "";
            }

            $qr_information = new QrInformation;
            $qr_information->background_panel = $request->background_color;
            $qr_information->business = $request->empresa_nombre;
            $qr_information->video_title = $request->empresa_titulo;
            $qr_information->description = $request->empresa_descripcion;
            $qr_information->link_fb = $request->red_fb ? $request->empresa_red_fb : "";
            $qr_information->link_tw = $request->red_tw ? $request->empresa_red_tw : "";
            $qr_information->link_tk = $request->red_tik ? $request->empresa_red_tik : "";
            $qr_information->img_welcome = $info_url;
            $qr_information->public_id_img = $info_public_id;
            $qr_information->qr_id = $qr->id;
            $qr_information->save();

            if ($request->hasFile('qr_imagen_medio')) {
                $file = $request->file('qr_imagen_medio');
                $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'imagesqr']);
                $design_public_id = $obj->getPublicId();
                $design_url = $obj->getSecurePath();
                // $file_medio = $request->file('qr_imagen_medio');
                // $name_medio = time() . $file_medio->getClientOriginalName();
                // $filePath_medio = 'images/' . $name_medio;
                // Storage::disk('s3')->put($filePath_medio, file_get_contents($file_medio));
            } else {
                $design_public_id = "";
                $design_url = "";
            }

            $qr_desing = new QrDesign;
            $qr_desing->dots_style = $request->qr_modelo_qr;
            $qr_desing->dots_color = $request->qr_color_model;
            $qr_desing->corners_style = $request->qr_modelo_bolita;
            $qr_desing->corners_color = $request->qr_color_modelo_bolita;
            $qr_desing->background_color = $request->qr_color_fondo;
            $qr_desing->image_center = $design_url;
            $qr_desing->public_id_img = $design_public_id;
            $qr_desing->qr_id = $qr->id;
            $qr_desing->save();

            $qr_public = new QrVisits;
            $qr_public->uuid = $request->uuid_public;
            $qr_public->visit = 0;
            $qr_public->so = "";
            $qr_public->contry = "";
            $qr_public->city = "";
            $qr_public->is_active = 1;
            $qr_public->qr_id = $qr->id;
            $qr_public->save();

            DB::commit();
            $response["status"] = "successs";
            $response["message"] = "Data guardado con éxito..!";
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response["status"] = "failed";
            $response["message"] = "Failed! image(s) not uploaded";
            return response()->json($e->getMessage());
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
        $qrs = Qr::with(['QrDesign', 'QrInformation'])->where("uuid", $id)->first();
        return response()->json(['data' => $qrs, 'message' => 'Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre_proyecto' => 'required',
                'video_url' => 'required',
                'embed_code' => 'required',
                'uuid_public' => 'required',
                'uuid_visit' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "Validation error", "errors" => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            Qr::where('uuid', $uuid)->update([
                'name' => $request->nombre_proyecto,
                'url_video' => $request->video_url,
                'embed_code' => $request->embed_code,
                'video_description' => $request->video_descipcion,
                'uuid_public' => $request->uuid_public,
                'uuid_visit' => $request->uuid_visit,
            ]);

            if ($request->hasFile('image_welcome')) {
                $file = $request->file('image_welcome');
                $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'imagesqr']);
                $info_public_id_edit = $obj->getPublicId();
                $info_url_edit = $obj->getSecurePath();
            } else {
                $info_public_id_edit = $request->public_id_img ? $request->public_id_img : "";
                $info_url_edit = $request->image_welcome ? $request->image_welcome : "";
            }

            QrInformation::where('qr_id', $request->id_edit)->update([
                'background_panel' => $request->background_color,
                'business' => $request->empresa_nombre,
                'video_title' => $request->empresa_titulo,
                'description' => $request->empresa_descripcion,
                'link_fb' => $request->red_fb  && $request->empresa_red_fb  != 'null' ? $request->empresa_red_fb  : "",
                'link_tw' => $request->red_tw  && $request->empresa_red_tw  != 'null' ? $request->empresa_red_tw  : "",
                'link_tk' => $request->red_tik && $request->empresa_red_tik != 'null' ? $request->empresa_red_tik : "",
                'img_welcome' => $info_url_edit,
                'public_id_img' => $info_public_id_edit,
            ]);

            if ($request->hasFile('qr_imagen_medio')) {
                $file = $request->file('qr_imagen_medio');
                $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'imagesqr']);
                $design_imagen_center_edit = $obj->getSecurePath();
                $design_public_id_edit = $obj->getPublicId();
            } else {
                $design_imagen_center_edit = $request->qr_imagen_medio ? $request->qr_imagen_medio : "";
                $design_public_id_edit = $request->qr_imagen_medio_id ? $request->qr_imagen_medio_id : "";
            }

            QrDesign::where('qr_id', $request->id_edit)->update([
                'dots_style' => $request->qr_modelo_qr,
                'dots_color' => $request->qr_color_model,
                'corners_style' => $request->qr_modelo_bolita,
                'corners_color' => $request->qr_color_modelo_bolita,
                'background_color' => $request->qr_color_fondo,
                'image_center' => $design_imagen_center_edit,
                'public_id_img' => $design_public_id_edit,
            ]);

            DB::commit();
            $response["status"] = "successs";
            $response["message"] = "Datos actualizados con éxito..!";
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response["status"] = "failed";
            $response["message"] = "Error en actualizar los datos.";
            return response()->json($e->getMessage());
        }


        $data = [
            "uuid" => $uuid,
            "url_Data" => $request->input("nombre_proyecto")
        ];
        return response()->json(['data' => $data, 'message' => 'Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Qr::where('uuid', $id)->delete();
            DB::commit();
            $response["status"] = "successs";
            $response["message"] = "Datos eliminados con éxito..!";
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
