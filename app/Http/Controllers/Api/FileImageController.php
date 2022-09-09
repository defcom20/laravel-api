<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileImageController extends Controller
{
    public function show ($path, $name){
        return Storage::disk('s3')->response($path.'/'.$name);
    }
}
