<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function general(Request $request)
    {
        return view('backend.administration.settings.general');
    }

    public function store_settings(Request $request)
    {
        foreach($request->except('_token') as $key => $value){

            if($key != '' && $value != ''){

                $data = array();
                $data['value'] = is_array($value) ? serialize($value) : $value; 
                $data['updated_at'] = now();

                if ($request->hasFile($key)) {
                    $image = $request->file($key);
                    $name = $key . '.' .$image->getClientOriginalExtension();
                    $path = public_path('uploads/images/');
                    $image->move($path, $name);
                    $data['value'] = $name; 
                    $data['updated_at'] = now();
                }
                if(Settings::where('name', $key)->exists()){                
                    Settings::where('name','=', $key)->update($data);         
                }else{
                    $data['name'] = $key; 
                    $data['created_at'] = now();
                    Settings::insert($data); 
                }
            }
        }
        if(! $request->ajax()){
            return back()->with('success', _lang('Saved sucessfully!'));
        }else{
            return response()->json(['result' => 'success', 'message' => _lang('Saved sucessfully.')]);
        }
    }
}
