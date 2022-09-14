<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use App\Models\Serial;
use App\Models\AppModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function serials(){

        $status = false;
        $base_url = url('/');

        $serials = Serial::select('serial_unique_id', 'serial_name',
        DB::raw("CASE WHEN cover_image_type = 'url' THEN cover_url WHEN cover_image_type = 'image' THEN CONCAT('$base_url/', cover_image) END AS image"))
        ->get();
        

        if($serials)
        {
            $status = true;
            return response()->json(['status' => true, 'data' => $serials], 200);
        }
        abort(404);
    }
}
