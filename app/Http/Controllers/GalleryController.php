<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('manager')) {
            $galleries = Gallery::where('organ_id', auth()->user()->organ_id)->latest()->get();
        } elseif ($user->hasRole('operator')) {
            $galleries = Gallery::where('operator_id', auth()->id())->latest()->get();
        } else {
            $galleries = Gallery::whereNull('organ_id')->whereNull('operator_id')->latest()->get();
        }
        return view('dashboard.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('dashboard.galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            'description' => 'nullable|string',
        ]);
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mimeType = $file->getMimeType();
            $type = str_starts_with($mimeType, 'image') ? 'image' : (str_starts_with($mimeType, 'video') ? 'video' : null);
            if (!$type) {
                return back()->with('fail', 'فایل انتخاب‌شده باید تصویر یا ویدیو باشد.');
            }
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/galleries';
            $file->move($destination_path, $file_name);
            $path = $destination_path . '/' . $file_name;
        }


        $user = User::find(Auth::user()->id);
        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $type,
            'path' => $path,
            'organ_id' => Auth::user()->organ_id,
            'operator_id' => $user->hasRole('operator') ? Auth::id() : null,
        ]);

        return redirect()->route('galleries.index')->with('success', 'با موفقیت ذخیره شد');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'با موفقیت حذف شد');
    }
}
