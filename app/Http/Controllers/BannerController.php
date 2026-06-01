<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Organ;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('manager')) {
            $banners = Banner::where('organ_id', auth()->user()->organ_id)->latest()->paginate(10);
        } else {
            $banners = Banner::whereNull('organ_id')->latest()->paginate(10);
        }
        return view('dashboard.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('dashboard.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:4048',
            'link' => 'nullable|url',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/banners';
            $file->move($destination_path, $file_name);
            $validated['image'] = $destination_path . '/' . $file_name;
        }
        $validated['organ_id'] = auth()->user()->organ_id ?? null;

        Banner::create($validated);

        return redirect()->route('banners.index')->with('success', 'بنر با موفقیت افزوده شد.');
    }

    public function edit(Banner $banner)
    {
        return view('dashboard.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'link' => 'nullable|url',
            'status' => 'required|boolean',
        ]);

        // if ($request->hasFile('image')) {
        //     Storage::disk('public')->delete($banner->image);
        //     $validated['image'] = $request->file('image')->store('banners', 'public');
        // }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/banners';
            $file->move($destination_path, $file_name);
            $validated['image'] = $destination_path . '/' . $file_name;
        }

        $banner->update($validated);

        return redirect()->route('banners.index')->with('success', 'بنر با موفقیت ویرایش شد.');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image);
        $banner->delete();

        return back()->with('success', 'بنر حذف شد.');
    }
}
