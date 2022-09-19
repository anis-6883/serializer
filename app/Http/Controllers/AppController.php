<?php

namespace App\Http\Controllers;

use DB;
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
            
            'app_unique_id' => 'required|string|max:127',
            'app_name' => 'required|string|max:127',
            'app_logo' => 'nullable|image',
            'notification_type' => 'required|string|max:31',
            'onesignal_app_id' => 'nullable|required_if:notification_type,==,onesignal|string|max:255',
            'onesignal_api_key' => 'nullable|required_if:notification_type,==,onesignal|string|max:255',
            'firebase_server_key' => 'nullable|required_if:notification_type,==,fcm|string|max:255',
            'firebase_topics' => 'nullable|required_if:notification_type,==,fcm|string|max:255',
            'app_publishing_control' => 'required|string|max:31',
            'ads_control' => 'required|string|max:31',
            'ios_app_publishing_control' => 'required|string|max:31',
            'ios_ads_control' => 'required|string|max:31',
            'enable_countries' => 'required|array',
            'status' => 'required',
            'google_app_id' => 'required|string',
            'google_appOpenAd_id' => 'required|string',
            'google_banner_ads' => 'required|string',
            'google_interstitial_ads' => 'required|string',
            'required_app_enable' => 'required|string',
            'required_app_id' => 'required|string',
            'required_app_url' => 'required|url',
            'required_app_name' => 'required|string',
            'required_app_logo' => 'nullable|image',

        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            }else{
                return back()->withErrors($validator)->withInput();
            }           
        }

        DB::beginTransaction();

        $app = new AppModel();
         
        $app->app_unique_id  = $request->app_unique_id ;
        $app->app_name = $request->app_name;
        $app->notification_type = $request->notification_type;
        $app->onesignal_app_id = $request->onesignal_app_id;
        $app->onesignal_api_key = $request->onesignal_api_key;
        $app->firebase_server_key = $request->firebase_server_key;
        $app->firebase_topics = $request->firebase_topics;
        $app->app_publishing_control = $request->app_publishing_control;
        $app->ads_control = $request->ads_control;
        $app->ios_app_publishing_control = $request->ios_app_publishing_control;
        $app->ios_ads_control = $request->ios_ads_control;
        $app->google_app_id = $request->google_app_id;
        $app->google_appOpenAd_id = $request->google_appOpenAd_id;
        $app->google_banner_ads = $request->google_banner_ads;
        $app->google_interstitial_ads = $request->google_interstitial_ads;
        $app->enable_countries = json_encode($request->enable_countries);
        $app->status = $request->status;
        $app->required_app_enable = $request->required_app_enable;
        $app->required_app_id = $request->required_app_id;
        $app->required_app_url = $request->required_app_url;
        $app->required_app_name = $request->required_app_name;
        $app->required_app_desc = $request->required_app_desc;
         
        if($request->hasFile('app_logo')){
            $file = $request->file('app_logo');
            $file_name = 'APP_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/images/apps/'), $file_name);
            $app->app_logo = 'public/uploads/images/apps/' . $file_name;
        }

        if($request->hasFile('required_app_logo')){
            $file = $request->file('required_app_logo');
            $file_name = 'APP_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/images/apps/'), $file_name);
            $app->required_app_logo = 'public/uploads/images/apps/' . $file_name;
        }
        
        $app->save();

        DB::commit();

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
            'notification_type' => 'required|string|max:31',
            'onesignal_app_id' => 'nullable|required_if:notification_type,==,onesignal|string|max:255',
            'onesignal_api_key' => 'nullable|required_if:notification_type,==,onesignal|string|max:255',
            'firebase_server_key' => 'nullable|required_if:notification_type,==,fcm|string|max:255',
            'firebase_topics' => 'nullable|required_if:notification_type,==,fcm|string|max:255',
            'app_publishing_control' => 'required|string|max:20',
            'ads_control' => 'required|string|max:20',
            'ios_app_publishing_control' => 'required|string|max:20',
            'ios_ads_control' => 'required|string|max:20',
            'enable_countries' => 'required',
            'status' => 'required',
            'google_app_id' => 'required|string',
            'google_appOpenAd_id' => 'required|string',
            'google_banner_ads' => 'required|string',
            'google_interstitial_ads' => 'required|string',
            'required_app_enable' => 'required|string',
            'required_app_id' => 'required|string',
            'required_app_url' => 'required|url',
            'required_app_name' => 'required|string',
            'required_app_logo' => 'nullable|image',
        ]);
 
        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            }else{
                return back()->withErrors($validator)->withInput();
            }           
        }

        DB::beginTransaction();

        $user = Auth::user();
        
        if($user->user_type == 'admin'){
            $app = AppModel::find($id);
        }else{
            $app = AppModel::find($id);
            //  $user_apps = $user->apps->pluck('app_id');
            //  $app = AppModel::whereIn('id', $user_apps)->first();
        }
 
        $app->app_name = $request->app_name;
        $app->notification_type = $request->notification_type;
        $app->onesignal_app_id = $request->onesignal_app_id;
        $app->onesignal_api_key = $request->onesignal_api_key;
        $app->firebase_server_key = $request->firebase_server_key;
        $app->firebase_topics = $request->firebase_topics;
        $app->app_publishing_control = $request->app_publishing_control;
        $app->ads_control = $request->ads_control;
        $app->ios_app_publishing_control = $request->ios_app_publishing_control;
        $app->ios_ads_control = $request->ios_ads_control;
        $app->enable_countries = json_encode($request->enable_countries);
        $app->status = $request->status;
        $app->google_app_id = $request->google_app_id;
        $app->google_appOpenAd_id = $request->google_appOpenAd_id;
        $app->google_banner_ads = $request->google_banner_ads;
        $app->google_interstitial_ads = $request->google_interstitial_ads;
        $app->required_app_enable = $request->required_app_enable;
        $app->required_app_id = $request->required_app_id;
        $app->required_app_url = $request->required_app_url;
        $app->required_app_name = $request->required_app_name;
        $app->required_app_desc = $request->required_app_desc;

        $prevImageName1 = $app->app_logo;
        $prevImageName2 = $app->required_app_logo;

        if($request->hasFile('app_logo'))
        {
            $file = $request->file('app_logo');
            $file_name = 'APP_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/images/apps/'), $file_name);
            $app->app_logo = 'public/uploads/images/apps/' . $file_name;

            if($prevImageName1 != "public/default/app.png")
            {
                if(File::exists($prevImageName1))
                    File::delete($prevImageName1);
            }
        }

        if($request->hasFile('required_app_logo'))
        {
            $file = $request->file('required_app_logo');
            $file_name = 'APP_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/images/apps/'), $file_name);
            $app->required_app_logo = 'public/uploads/images/apps/' . $file_name;

            if($prevImageName2 != "public/default/app.png")
            {
                if(File::exists($prevImageName2))
                    File::delete($prevImageName2);
            }
        }
 
        $app->save();

        DB::commit();
 
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
        $img_path2 = $app->required_app_logo;

        if($img_path != "public/default/app.png")
        {
            if(File::exists($img_path))
                File::delete($img_path);

            if(File::exists($img_path2))
                File::delete($img_path2);
        }

        $app->delete();

        if (!$request->ajax()) {
            return back()->with('success', _lang('Information has been deleted!'));
        } else {
            return response()->json(['result' => 'success', 'message' => _lang('Information has been deleted sucessfully')]);
        }
    }
}
