<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use Validator;
use App\Models\Serial;  
use App\Models\Episode;
use App\Models\AppModel;
use App\Models\StreamingSource;
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

    public function episodes(Request $request)
    {
        $status = false;
        $base_url = url('/');
        
        $validator = Validator::make($request->all(), [
            
            'serial_id' => 'required|string|exists:serials,serial_unique_id',
            'app_id' => 'required|string|exists:apps,app_unique_id',

        ]);
 
        if ($validator->fails())
            return response()->json(['status' => $status, 'message' => 'Please, Provide Valid Key!'], 401);

        $app_unique_id = $request->app_id;
        $serial_unique_id = $request->serial_id;

        $query = Episode::select('episodes.id', 'episodes.episode_title', 
        DB::raw("CASE WHEN episodes.cover_image_type = 'url' THEN episodes.cover_url WHEN episodes.cover_image_type = 'image' THEN CONCAT('$base_url/', episodes.cover_image) ELSE '' END AS image"),
        DB::raw('UNIX_TIMESTAMP(episode_date) AS episode_time'))
        ->join('serials', 'serials.id', '=', 'episodes.serial_id')
        ->join('episode_apps', 'episode_apps.episode_id', '=', 'episodes.id')
        ->join('apps', 'apps.id', '=', 'episode_apps.app_id')
        ->where('serials.serial_unique_id', $serial_unique_id)
        ->where('apps.app_unique_id', $app_unique_id)
        ->get()
        ->makeHidden(['id']);

        $episodes = [];
        $i = 0;

        foreach ($query as $key => $data) 
        {
            $episodes[$key] = $data;
            // $episodes[$key]->episode_links = StreamingSource::select('stream_title', 'resulation', 'stream_type', 'stream_url')->where('episode_id', $data->id)->get();
            $streaming_sources = array();
            $streamingSources = StreamingSource::where('episode_id', $data->id)->get();

            foreach ($streamingSources as $key2 => $source) 
            {
                if($source->stream_type == 'restricted')
                {
                    $headers = array();
                    $i2 = 0;
                    foreach(json_decode($source->headers, true) AS $key => $value)
                    {
                        // if($key != 'User-Agent')
                        // {
                        // 	$headers[$i2]['name'] = $key;
                        // 	$headers[$i2]['value'] = $value;
                        // 	$i2 ++;
                        // }
                        // else
                        // 	$source->$key = $value;

                        $headers[$i2]['name'] = $key;
                        $headers[$i2]['value'] = $value;
                        $i2 ++;
                    }
                    $source->headers = $headers;
                }
                else
                    $source->headers = [];

                $streaming_sources[] = $source->makeHidden(['id', 'episode_id', 'created_at', 'updated_at']);
            }
            $episodes[$i]['streaming_sources'] = $streaming_sources;
            $i++;
        }

        if($episodes)
        {
            $status = true;
            return response()->json(['status' => $status, 'data' => $episodes], 200);
        }
        
    }

    public function settings(Request $request)
    {
        $status = false;
        $base_url = url('/');

        $validator = Validator::make($request->all(), [
            'app_id' => 'required|string|exists:apps,app_unique_id',
        ]);
 
        if ($validator->fails())
            return response()->json(['status' => $status, 'message' => 'Please, Provide Valid Key!'], 401);

        $app = AppModel::select('apps.*',
            DB::raw("CONCAT('$base_url/', required_app_logo) AS required_app_logo"))
            ->where('app_unique_id', $request->app_id)
            ->first()
            ->makeHidden(['id', 'app_unique_id', 'app_logo', 'app_name', 'enable_countries', 'onesignal_app_id', 'onesignal_api_key', 'status', 'created_at', 'updated_at']);

        if($app)
        {
            $status = true;
            return response()->json([
                'status' => $status, 
                'data' => $app], 200);
        }
        else
            return response()->json(['status' => false, 'message' => 'Please, Provide Valid Key!'], 401);
    }
}

