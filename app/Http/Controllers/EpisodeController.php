<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Cache;
use Validator;
use DataTables;
use App\Models\Serial;
use App\Models\Episode;
use App\Models\AppModel;
use App\Models\EpisodeApp;
use Illuminate\Http\Request;
use App\Models\StreamingSource;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $episodes = Episode::orderBy('id', 'DESC');

        if($request->ajax())
        {
            return DataTables::of($episodes)
            ->addColumn('episode_image', function ($episode) {
                if($episode->cover_image_type == 'url'){
                    return '<div style=" white-space: nowrap; ">
                    <img class="img-sm img-thumbnail" src="' . $episode->cover_url . '">
                    </div>';
                }
                if($episode->cover_image_type == 'image'){
                    return '<div style=" white-space: nowrap; ">
                    <img class="img-sm img-thumbnail" src="' . asset($episode->cover_image) . '">
                    </div>';
                }
                return '<div style=" white-space: nowrap; ">
                    <img class="img-sm img-thumbnail" src="' . asset('public/default/episode.png') . '">
                    </div>';
            })
            ->addColumn('apps', function ($episode) {
                $apps_name = '';

                foreach (\App\Models\EpisodeApp::where('episode_id', $episode->id)->join('apps', 'apps.id', 'app_id')->get() as $app){
                    $apps_name .= $app->app_name . ', ';
                }

                return $apps_name;
            })
            ->addColumn('serial', function ($episode) {
                $serial = Episode::select('serial_name')
                ->join('serials', 'episodes.serial_id', '=', 'serials.id')
                ->where('episodes.id', $episode->id)
                ->first();

                return $serial->serial_name;
            })
            ->addColumn('status', function ($episode) {
                return $episode->status == 1 ? status(_lang('Active'), 'success') : status(_lang('In-Active'), 'danger');
            })
            ->addColumn('action', function($episode){

                $action = '<div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ' . _lang('Action') . '
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                $action .= '<a href="' . route('episodes.edit', $episode->id) . '" class="dropdown-item">
                                    <i class="fas fa-edit"></i>
                                    ' . _lang('Edit') . '
                                </a>';
                
                $action .= '<form action="' . route('episodes.destroy', $episode->id) . '" method="post" class="ajax-delete">'
                            . csrf_field() 
                            . method_field('DELETE') 
                            . '<button type="button" class="btn-remove dropdown-item">
                                    <i class="fas fa-trash-alt"></i>
                                    ' . _lang('Delete') . '
                                </button>
                            </form>';
                $action .= '</div>
                        </div>';
                return $action;
            })

            ->rawColumns(['episode_image', 'apps', 'serial', 'action', 'status'])
            ->make(true);
        }
        return view('backend.episodes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if($user->user_type == 'admin'){
            $apps = AppModel::where('status', 1)->get();
            $serials = Serial::where('status', 1)->orderBy('id', 'DESC')->get();
        }else{
            $apps = AppModel::where('status', 1)->get();
            $serials = Serial::where('status', 1)->orderBy('id', 'DESC')->get();
        }
        return view('backend.episodes.create', compact('apps', 'serials'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'apps' => 'required',
            'serial_id' => 'required',
            'episode_title' => 'required|string|max:191',
			'cover_image_type' => 'required|string|max:20',
            'cover_url' => 'nullable|required_if:cover_image_type,url|url',
            'cover_image' => 'required_if:cover_image_type,image|image',
            'status' => 'required',

            'stream_title' => 'required',
            'stream_title.*' => 'required',
            'stream_type' => 'required',
            'stream_type.*' => 'required',
            'stream_url' => 'required',
            'stream_url.*' => 'required',
            'resulation' => 'required',
            'resulation.*' => 'required',
            'name' => 'nullable|required_if:stream_type,restricted',
            'name.*' => 'nullable|required_if:stream_type,restricted',
            'name.*.*' => 'nullable|required_if:stream_type,restricted',
            'value' => 'nullable|required_if:stream_type,restricted',
            'value.*' => 'nullable|required_if:stream_type,restricted',
            'value.*.*' => 'nullable|required_if:stream_type,restricted',

        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        DB::beginTransaction();

        $episode = new Episode();

        $episode->serial_id = $request->serial_id;
        $episode->episode_title = $request->episode_title;
		$episode->cover_image_type = $request->cover_image_type;
        $episode->cover_url = $request->cover_url;
        $episode->episode_date = $request->episode_date;
        $episode->status = $request->status;

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $ImageName = time() . '.' . $image->getClientOriginalExtension();
            // \Image::make($image)->save(base_path('public/uploads/images/episodees/') . $ImageName);
            $image->move(base_path('public/uploads/images/episodes/'), $ImageName);
            $episode->cover_image = 'public/uploads/images/episodes/' . $ImageName;
        }

        $episode->save();

        for ($i=0; $i < count($request->apps); $i++) { 
            
            $app = new EpisodeApp();

            $app->app_id = $request->apps[$i];
            $app->episode_id = $episode->id;

            $app->save();
			
			$appData = AppModel::where('id', $app->app_id)->first();
			Cache::forget("episodes_" . $appData->app_unique_id);
        }

        // dd($request->all());

        for ($i=0; $i < count($request->stream_title); $i++) { 

            if($request->stream_title[$i] != '' && $request->stream_type[$i] != '' && $request->stream_url[$i] != ''){

                $headers = '';

                if ($request->stream_type[$i] == 'restricted') {
                    $h = array();
                    if(isset($request->name[$i]) && isset($request->value[$i])){
                        for ($i2=0; $i2 < count($request->name[$i]); $i2++) { 
                            if($request->name[$i][$i2] != null && $request->value[$i][$i2] != null){
                                $h[$request->name[$i][$i2]] = $request->value[$i][$i2];
                            }
                        }
                    }
                    $headers = json_encode($h);
                }

                $streaming_source = new StreamingSource();

                $streaming_source->episode_id = $episode->id;
                $streaming_source->stream_title = $request->stream_title[$i];
                $streaming_source->resulation = $request->resulation[$i];
                $streaming_source->stream_type = $request->stream_type[$i];
                $streaming_source->stream_url = $request->stream_url[$i];
                $streaming_source->headers = $request->stream_type[$i] == 'restricted' ? $headers : '';
                
                $streaming_source->save();
            }
        }

        DB::commit();
        
        Cache::forget("episode_$episode->id");
        Cache::forget("streamingSources_$episode->id");

        if (!$request->ajax()) {
            return redirect('episodes')->with('success', _lang('Information has been added sucessfully!'));
        } else {
            return response()->json(['result' => 'success', 'redirect' => url('episodes'), 'message' => _lang('Information has been added sucessfully!')]);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $episode = Episode::find($id);

        $user = Auth::user();
        if($user->user_type == 'admin'){
            $apps = AppModel::where('status', 1)->get();
            $serials = Serial::where('status', 1)->orderBy('id', 'DESC')->get();
        }else{
            $apps = AppModel::where('status', 1)->get();
            $serials = Serial::where('status', 1)->orderBy('id', 'DESC')->get();
        }
        return view('backend.episodes.edit', compact('episode', 'apps', 'serials'));
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [

            'apps' => 'required',
            'serial_id' => 'required',
            'episode_title' => 'required|string|max:191',
			'cover_image_type' => 'required|string|max:20',
            'cover_url' => 'nullable|required_if:cover_image_type,url|url',
            'cover_image' => 'nullable|image',
            'status' => 'required',

            'stream_title' => 'required',
            'stream_title.*' => 'nullable|required_if:is_deleted,no',
            'stream_type' => 'required',
            'stream_type.*' => 'nullable|required_if:is_deleted,no',
            'stream_url' => 'required',
            'stream_url.*' => 'nullable|required_if:is_deleted,no',
            'resulation' => 'required',
            'resulation.*' => 'nullable|required_if:is_deleted,no',
            'name' => 'nullable|required_if:stream_type,restricted',
            'name.*' => 'nullable|required_if:stream_type,restricted',
            'name.*.*' => 'nullable|required_if:stream_type,restricted',
            'value' => 'nullable|required_if:stream_type,restricted',
            'value.*' => 'nullable|required_if:stream_type,restricted',
            'value.*.*' => 'nullable|required_if:stream_type,restricted',

        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        DB::beginTransaction();

        $episode = Episode::find($id);

        $episode->serial_id = $request->serial_id;
        $episode->episode_title = $request->episode_title;
		$episode->cover_image_type = $request->cover_image_type;
        $episode->cover_url = $request->cover_url;
        $episode->episode_date = $request->episode_date;
        $episode->status = $request->status;

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $ImageName = time() . '.' . $image->getClientOriginalExtension();
            // \Image::make($image)->save(base_path('public/uploads/images/episodees/') . $ImageName);
            $image->move(base_path('public/uploads/images/episodes/'), $ImageName);
            $episode->cover_image = 'public/uploads/images/episodes/' . $ImageName;
        }

        $episode->save();

        EpisodeApp::where('episode_id', $episode->id)->delete();
        for ($i=0; $i < count($request->apps); $i++) { 
            
            $app = new EpisodeApp();

            $app->app_id = $request->apps[$i];
            $app->episode_id = $episode->id;

            $app->save();
			
			$appData = AppModel::where('id', $app->app_id)->first();
			Cache::forget("episodes_" . $appData->app_unique_id);
        }

        StreamingSource::where('episode_id', $episode->id)->delete();
        for ($i=0; $i < count($request->stream_title); $i++) { 
            if($request->stream_title[$i] != '' && $request->stream_type[$i] != '' && $request->stream_url[$i] != '' && $request->is_deleted[$i] != 'yes'){

                $headers = '';

                if ($request->stream_type[$i] == 'restricted') {
                    $h = array();
                    if(isset($request->name[$i]) && isset($request->value[$i])){
                        for ($i2=0; $i2 < count($request->name[$i]); $i2++) { 
                            if($request->name[$i][$i2] != null && $request->value[$i][$i2] != null){
                                $h[$request->name[$i][$i2]] = $request->value[$i][$i2];
                            }
                        }
                    }
                    $headers = json_encode($h);
                }

                $streaming_source = new StreamingSource();

                $streaming_source->episode_id = $episode->id;
                $streaming_source->stream_title = $request->stream_title[$i];
                $streaming_source->resulation = $request->resulation[$i];
                $streaming_source->stream_type = $request->stream_type[$i];
                $streaming_source->stream_url = $request->stream_url[$i];
                $streaming_source->headers = $request->stream_type[$i] == 'restricted' ? $headers : '';
                $streaming_source->save();
            }
        }

        // return ($headers);

        DB::commit();

        Cache::forget("episode_$episode->id");
        Cache::forget("streamingSources_$episode->id");

        if (!$request->ajax()) {
            return redirect('episodes')->with('success', _lang('Information has been updated successfully!'));
        } else {
            return response()->json(['result' => 'success', 'redirect' => url('episodes'), 'message' => _lang('Information has been updated successfully!')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $episode = Episode::find($id);
        EpisodeApp::where('episode_id', $episode->id)->delete();
        StreamingSource::where('episode_id', $episode->id)->delete();
        $episode->delete();
		
		Cache::forget("episodes");
        Cache::forget("episode_$id");
        Cache::forget("streamingSources_$id");

        if (!$request->ajax()) {
            return back()->with('success', _lang('Information has been deleted successfully!'));
        } else {
            return response()->json(['result' => 'success', 'message' => _lang('Information has been deleted sucessfully!')]);
        }
    }
}
