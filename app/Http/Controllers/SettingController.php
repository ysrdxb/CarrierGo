<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('settings.index', compact('settings'));
    }

    public function create()
    {
        return view('settings.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|unique:settings',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Setting::create($request->all());

        SettingService::fillSession();

        return redirect()->route('settings.index')->with('success', 'Setting created successfully');
    }

    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'key' => 'required|unique:settings,key,' . $id,
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $setting->update($request->all());
        
        SettingService::fillSession();

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully');
    }
}
