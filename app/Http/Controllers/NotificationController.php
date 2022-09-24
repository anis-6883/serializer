<?php

namespace App\Http\Controllers;

use File;
use Auth;
use Cache;
use Carbon;
use Validator;
use DataTables;
use App\Models\Episode;
use App\Models\AppModel;
use App\Models\EpisodeApp;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notifications = Notification::orderBy('id', 'DESC');

        if ($request->ajax()) {
            return DataTables::of($notifications)
                ->addColumn('action', function($notification){

                    $action = '<div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . _lang('Action') . '
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    $action .= '<a href="' . route('notifications.edit', $notification->id) . '" class="dropdown-item">
                                        ' . _lang('Resend') . '
                                    </a>';
                    $action .= '<form action="' . route('notifications.destroy', $notification->id) . '" method="post" class="ajax-delete">'
                                . csrf_field() 
                                . method_field('DELETE') 
                                . '<button type="button" class="btn-remove dropdown-item">
                                        ' . _lang('Delete') . '
                                    </button>
                                </form>';
                    $action .= '</div>
                            </div>';
                    return $action;
                })
                ->setRowId(function ($notification) {
                    return "row_" . $notification->id;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.notifications.index', compact('notifications'));
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
        }else{
            $apps = AppModel::where('status', 1)->get();
        }
        return view('backend.notifications.create', compact('apps'));
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
            'title' => 'required|string|max:191',
            'body' => 'required',
            'image_type' => 'required|string|max:20',
            'image_url' => 'nullable|required_if:image_type,url|url',
            'image' => 'nullable|required_if:image_type,image|image',
            'notification_type' => 'required|string|max:20',
            'action_url' => 'nullable|required_if:notification_type,==,url|url|max:191',

        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        $image = '';

        $notification = new Notification();

        $notification->apps = json_encode($request->apps);
        $notification->title = $request->title;
        $notification->message = $request->body;
        $notification->image_type = $request->image_type;
        $notification->image_url = $request->image_url;
        $notification->notification_type = $request->notification_type;
        $notification->action_url = $request->action_url;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ImageName = 'NOTIFICATION_' . time() . "_" . rand() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/notifications/'), $ImageName);
            $notification->image = 'public/uploads/images/notifications/' . $ImageName;
        }

        $notification->save();

        if($request->image_type == 'url'){
            $image = $request->image_url;
        }elseif ($request->image_type == 'image') {
            $image = asset($notification->image);
        }

        foreach ($request->apps as $key => $value) {

            $app = AppModel::where('id', $value)->first();

            $data = [
                'notification_type' => $notification->notification_type,
            ];

            send_notification($app, $notification->title, $notification->message, $image, $data);
        }

        if (!$request->ajax()) {
            return redirect('notifications')->with('success', _lang('Notification sent!'));
        } else {
            return response()->json(['result' => 'success', 'redirect' => url('/notifications'), 'message' => _lang('Notification sent!')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $notification = Notification::find($id);
        $user = Auth::user();
        if($user->user_type == 'admin'){
            $apps = AppModel::where('status', 1)->get();
        }else{
            $apps = AppModel::where('status', 1)->get();
        }
        return view('backend.notifications.edit', compact('notification', 'apps'));
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

            'apps' => 'required',
            'title' => 'required|string|max:191',
            'body' => 'required',
            'image_type' => 'required|string|max:20',
            'image_url' => 'nullable|required_if:image_type,url|url',
            'image' => 'nullable|image',
            'notification_type' => 'required|string|max:20',
            'action_url' => 'nullable|required_if:notification_type,==,url|url|max:191',

        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        $image = '';

        $notification = new Notification();

        $notification->apps = json_encode($request->apps);
        $notification->title = $request->title;
        $notification->message = $request->body;
        $notification->image_type = $request->image_type;
        $notification->image_url = $request->image_url;
        $notification->notification_type = $request->notification_type;
        $notification->action_url = $request->action_url;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ImageName = 'NOTIFICATION_' . time() . "_" . rand() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/notifications/'), $ImageName);
            $notification->image = 'public/uploads/images/notifications/' . $ImageName;
        }

        $notification->save();

        if($request->image_type == 'url'){
            $image = $request->image_url;
        }elseif ($request->image_type == 'image' && $request->hasFile('image')) {
            $image = asset($notification->image);
        }else{
            $image = asset(Notification::find($id)->image);
        }

        foreach ($request->apps as $key => $value) {

            $app = AppModel::where('id', $value)->first();

            $data = [
                'notification_type' => $notification->notification_type,
            ];

            send_notification($app, $notification->title, $notification->message, $image, $data);
        }

        if (!$request->ajax()) {
            return redirect('notifications')->with('success', _lang('Notification sent!'));
        } else {
            return response()->json(['result' => 'success', 'redirect' => url('/notifications'), 'message' => _lang('Notification sent!')]);
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
        $notification = Notification::find($id);
        $img_path = $notification->image;

        if(File::exists($img_path))
            File::delete($img_path);

        $notification->delete();

        if (!$request->ajax()) {
            return back()->with('success', _lang('Information has been deleted'));
        } else {
            return response()->json(['result' => 'success', 'message' => _lang('Information has been deleted sucessfully')]);
        }
    }

    /**
     * Remove all the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteall(Request $request)
    {
        $notifications = Notification::all();

        foreach ($notifications as $notify) {
            $img_path = $notify->image;

            if(File::exists($img_path))
                File::delete($img_path);
        }
        
        Notification::truncate();

        if (!$request->ajax()) {
            return back()->with('success', _lang('Information has been deleted'));
        } else {
            return response()->json(['result' => 'success', 'message' => _lang('Information has been deleted sucessfully')]);
        }
    }

    public function upcomingEpisodeNotify()
    {
        $episodes = Episode::whereDate('episode_date', '=', Carbon::today())->get();

        foreach ($episodes as $episode) 
        {
            $actual_time = Carbon::parse($episode->episode_date);
            $before_five_min = Carbon::parse($episode->episode_date)->subMinutes(5);

            if($before_five_min->lessThanOrEqualTo(Carbon::now()) && $actual_time->greaterThan(Carbon::now()))
            {
                $episode_apps = EpisodeApp::where('episode_id', $episode->id)->get();

                if (!Cache::has('episode_id_' . $episode->id)) 
                {
                    Cache::put('episode_id_' . $episode->id, $episode->id, now()->addMinutes(5));

                    foreach ($episode_apps as $app)
                    {
                        $app = AppModel::find($app->app_id);

                        send_notification($app, $episode->episode_title, "Just Watch It...", $image = null, $data = []);
                    }
                }
            }
            else
            {
                echo "No Notify $episode->id <br>";
            }
        }
    }
}
