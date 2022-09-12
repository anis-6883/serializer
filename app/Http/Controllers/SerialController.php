<?php

namespace App\Http\Controllers;

use File;
use Auth;
use Validator;
use DataTables;
use App\Models\Serial;
use Illuminate\Http\Request;

class SerialController extends Controller
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
            $serials = Serial::orderBy('serial_order');
        else
        {
            $serials = Serial::orderBy('serial_order');
            // $user_serials = $user->serials->pluck('serial_id');
            // $serials = Serial::whereIn('id', $user_serials)->orderBy('id', 'DESC');
        }

        if($request->ajax())
        {
            return DataTables::of($serials)
                    
                    ->editColumn('serial_image', function($serial){

                        return '<img class="img-sm img-thumbnail" src="' . asset($serial->serial_image) . '">';
                    })
                    ->addColumn('_serial', function($serial){

                        return 'ID: <a href="javascript:void(0);">' . $serial->serial_unique_id . '</a><br><br>Name: ' . $serial->serial_name;
                    })
                    ->addColumn('status', function ($serial) {
                        return $serial->status == 1 ? status(_lang('Active'), 'success') : status(_lang('In-Active'), 'danger');
                    })
                    ->addColumn('action', function($serial) use ($user){

                        $action = '<div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            ' . _lang('Action') . '
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        $action .= '<a href="' . route('serials.edit', $serial->id) . '" class="dropdown-item">
                                        <i class="fas fa-edit"></i>
                                        ' . _lang('Edit') . '
                                    </a>';
                        if($user->user_type == 'admin'){
                            $action .= '<form action="' . route('serials.destroy', $serial->id) . '" method="post" class="ajax-delete">'
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
                    ->setRowId(function ($serial) {
                        return "row_" . $serial->id;
                    })
                    ->rawColumns(['action', 'serial_image', '_serial', 'status'])
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
        return view('backend.serials.create');
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
            
            'serial_unique_id' => 'required|string|max:191',
            'serial_name' => 'required|string|max:191',
            'serial_image' => 'nullable|image',
            'status' => 'required',
 
        ]);
 
        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            }else{
                return back()->withErrors($validator)->withInput();
            }           
        }

        $serial = new Serial();
         
        $serial->serial_unique_id  = $request->serial_unique_id ;
        $serial->serial_name = $request->serial_name;
        $serial->status = $request->status;

        if(Serial::count() != 0)
            $serial->serial_order = Serial::max('serial_order') + 1;
        else
            $serial->serial_order = 1;
         
        if($request->hasFile('serial_image')){
            $file = $request->file('serial_image');
            $file_name = 'SERIAL_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/images/serials/'), $file_name);
            $serial->serial_image = 'public/uploads/images/serials/' . $file_name;
        }
         
         $serial->save();
 
         if(! $request->ajax()){
             return redirect('/serials')->with('success', _lang('Information has been added sucessfully!'));
         }else{
             return response()->json(['result' => 'success', 'redirect' => url('serials'), 'message' => _lang('Information has been added sucessfully!')]);
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
        // $serial = Serial::findOrFail($id);
        // return view('backend.serials.show', compact('serial'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $serial = Serial::findOrFail($id);
        return view('backend.serials.edit', compact('serial'));
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
            
            'serial_unique_id' => 'required|string|max:191',
            'serial_name' => 'required|string|max:191',
            'serial_image' => 'nullable|image',
            'status' => 'required',
 
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
            $serial = Serial::find($id);
        }else{
            $serial = Serial::find($id);
            //  $user_serials = $user->serials->pluck('serial_id');
            //  $serial = Serial::whereIn('id', $user_serials)->first();
        }
         
        $serial->serial_unique_id  = $request->serial_unique_id ;
        $serial->serial_name = $request->serial_name;
        $serial->status = $request->status;

        $prevImageName = $serial->serial_image;
         
        if($request->hasFile('serial_image')){
            $file = $request->file('serial_image');
            $file_name = 'SERIAL_' . time() . "_" . rand() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/images/serials/'), $file_name);
            $serial->serial_image = 'public/uploads/images/serials/' . $file_name;

            if($prevImageName != "public/default/serial.png")
            {
                if(File::exists($prevImageName))
                    File::delete($prevImageName);
            }
        }
         
         $serial->save();
 
         if(! $request->ajax()){
             return redirect('/serials')->with('success', _lang('Information has been updated sucessfully!'));
         }else{
             return response()->json(['result' => 'success', 'redirect' => url('serials'), 'message' => _lang('Information has been updated sucessfully!')]);
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
        $serial = Serial::find($id);
        $img_path = $serial->serial_image;

        if($img_path != "public/default/serial.png")
        {
            if(File::exists($img_path))
                File::delete($img_path);
        }

        $serial->delete();

        if (!$request->ajax()) {
            return back()->with('success', _lang('Information has been deleted!'));
        } else {
            return response()->json(['result' => 'success', 'message' => _lang('Information has been deleted sucessfully')]);
        }
    }

    public function reordering(Request $request)
    {
        $serialOfData = json_decode($request->streaming_sources);

        foreach ($serialOfData as $data) { 
            $serial = Serial::find($data->id);
            $serial->serial_order = $data->position;
            $serial->save();  
        }

        return response()->json(['result' => 'success', 'message' => _lang('Information has been sorted sucessfully!')]);
    }
}
