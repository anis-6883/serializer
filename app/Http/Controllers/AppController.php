<?php

namespace App\Http\Controllers;

use File;
use Auth;
use Validator;
use DataTables;
use App\Models\AppModel;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if($user->user_type == 'admin')
            $apps = AppModel::orderBy('id', 'DESC');
        else
        {
            $apps = AppModel::orderBy('id', 'DESC');
            // $user_apps = $user->apps->pluck('app_id');
            // $apps = AppModel::whereIn('id', $user_apps)->orderBy('id', 'DESC');
        }

        if($request->ajax())
        {
            return DataTables::of($apps)
                    
                    ->editColumn('app_logo', function($app){

                        return '<img class="img-sm img-thumbnail" src="' . asset($app->app_logo) . '">';
                    })
                    ->addColumn('_app', function($app){

                        return 'ID: <a href="javascript:void(0);">' . $app->app_unique_id . '</a><br><br>Name: ' . $app->app_name;
                    })
                    ->addColumn('status', function ($app) {
                        return $app->status == 1 ? status(_lang('Active'), 'success') : status(_lang('In-Active'), 'danger');
                    })
                    ->addColumn('action', function($app) use ($user){

                        $action = '<div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            ' . _lang('Action') . '
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        $action .= '<a href="' . route('apps.edit', $app->id) . '" class="dropdown-item">
                                        <i class="fas fa-edit"></i>
                                        ' . _lang('Edit') . '
                                    </a>';
                        if($user->user_type == 'admin'){
                            $action .= '<form action="' . route('apps.destroy', $app->id) . '" method="post" class="ajax-delete">'
                                . csrf_field() 
                                . method_field('DELETE') 
                                . '<button type="submit" class="btn-remove dropdown-item">
                                        <i class="fas fa-trash-alt"></i>
                                        ' . _lang('Delete') . '
                                    </button>
                                </form>';
                        }
                        
                        $action .= '</div>
                                </div>';
                        return $action;
                    })
                    ->rawColumns(['action', 'app_logo', '_app', 'status'])
                    ->make(true);
        }

        return view('backend.apps.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.apps.create');
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
            
            'app_unique_id' => 'required|string|max:191',
            'app_name' => 'required|string|max:191',
            'app_logo' => 'nullable|image',
            'onesignal_app_id' => 'required|string|max:191',
            'onesignal_api_key' => 'required|string|max:191',
            'app_publishing_control' => 'required|string|max:20',
            'ads_control' => 'required|string|max:20',
            'ios_app_publishing_control' => 'required|string|max:20',
            'ios_ads_control' => 'required|string|max:20',
            'ios_share_link' => 'required|url',
            'privacy_policy' => 'nullable|string|max:191',
            'facebook' => 'nullable|string|max:191',
            'telegram' => 'nullable|string|max:191',
            'youtube' => 'nullable|string|max:191',
            'enable_countries' => 'required|array',
            'status' => 'required',
 
         ]);
 
         if ($validator->fails()) {
             if($request->ajax()){ 
                 return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
             }else{
                 return back()->withErrors($validator)->withInput();
             }           
         }
 
         $app = new AppModel();
         
         $app->app_unique_id  = $request->app_unique_id ;
         $app->app_name = $request->app_name;
         $app->onesignal_app_id = $request->onesignal_app_id;
         $app->onesignal_api_key = $request->onesignal_api_key;
         $app->app_publishing_control = $request->app_publishing_control;
         $app->ads_control = $request->ads_control;
         $app->ios_share_link = $request->ios_share_link;
         $app->ios_app_publishing_control = $request->ios_app_publishing_control;
         $app->ios_ads_control = $request->ios_ads_control;
         $app->privacy_policy = $request->privacy_policy;
         $app->facebook = $request->facebook;
         $app->telegram = $request->telegram;
         $app->youtube = $request->youtube;
         $app->enable_countries = json_encode($request->enable_countries);
         $app->status = $request->status;
         
         if($request->hasFile('app_logo')){
             $file = $request->file('app_logo');
             $file_name = 'APP_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
             $file->move(base_path('public/uploads/images/apps/'), $file_name);
             $app->app_logo = 'public/uploads/images/apps/' . $file_name;
         }
         
         $app->save();
 
         if(! $request->ajax()){
             return redirect('/apps')->with('success', _lang('Information has been added sucessfully!'));
         }else{
             return response()->json(['result' => 'success', 'redirect' => url('apps'), 'message' => _lang('Information has been added sucessfully!')]);
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
        // $app = AppModel::findOrFail($id);
        // return view('backend.apps.show', compact('app'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $app = AppModel::findOrFail($id);
        return view('backend.apps.edit', compact('app'));
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
        $validator = Validator::make($request->all(), [
            
            'app_name' => 'required|string|max:191',
            'app_logo' => 'nullable|image',
            'onesignal_app_id' => 'required|string|max:191',
            'onesignal_api_key' => 'required|string|max:191',
            'app_publishing_control' => 'required|string|max:20',
            'ads_control' => 'required|string|max:20',
            'ios_app_publishing_control' => 'required|string|max:20',
            'ios_ads_control' => 'required|string|max:20',
            'ios_share_link' => 'required|url',
            'privacy_policy' => 'nullable|string|max:191',
            'facebook' => 'nullable|string|max:191',
            'telegram' => 'nullable|string|max:191',
            'youtube' => 'nullable|string|max:191',
            'enable_countries' => 'required',
            'status' => 'required'
 
         ]);
 
         if ($validator->fails()) {
             if($request->ajax()){ 
                 return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
             }else{
                 return back()->withErrors($validator)->withInput();
             }           
         }
 
         $user = Auth::user();
         
         if($user->user_type == 'admin'){
            $app = AppModel::find($id);
         }else{
            $app = AppModel::find($id);
            //  $user_apps = $user->apps->pluck('app_id');
            //  $app = AppModel::whereIn('id', $user_apps)->first();
         }
 
         $app->app_name = $request->app_name;
         $app->onesignal_app_id = $request->onesignal_app_id;
         $app->onesignal_api_key = $request->onesignal_api_key;
         $app->app_publishing_control = $request->app_publishing_control;
         $app->ads_control = $request->ads_control;
         $app->ios_share_link = $request->ios_share_link;
         $app->ios_app_publishing_control = $request->ios_app_publishing_control;
         $app->ios_ads_control = $request->ios_ads_control;
         $app->privacy_policy = $request->privacy_policy;
         $app->facebook = $request->facebook;
         $app->telegram = $request->telegram;
         $app->youtube = $request->youtube;
         $app->enable_countries = json_encode($request->enable_countries);
         $app->status = $request->status;

         $prevImageName = $app->app_logo;

         if($request->hasFile('app_logo')){
             $file = $request->file('app_logo');
             $file_name = 'APP_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
             $file->move(base_path('public/uploads/images/apps/'), $file_name);
             $app->app_logo = 'public/uploads/images/apps/' . $file_name;

             if($prevImageName != "public/default/app.png")
             {
                if(File::exists($prevImageName))
                    File::delete($prevImageName);
             }
             
         }
 
         $app->save();
 
         if(! $request->ajax()){
             return redirect('apps')->with('success', _lang('Information has been updated sucessfully!'));
         }else{
             return response()->json(['result' => 'success', 'redirect' => url('apps'), 'message' => _lang('Information has been updated sucessfully')]);
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
        $app = AppModel::find($id);
        $img_path = $app->app_logo;

        if($img_path != "public/default/app.png")
        {
            if(File::exists($img_path))
                File::delete($img_path);
        }

        $app->delete();

        if (!$request->ajax()) {
            return back()->with('success', _lang('Information has been deleted!'));
        } else {
            return response()->json(['result' => 'success', 'message' => _lang('Information has been deleted sucessfully')]);
        }
    }
}