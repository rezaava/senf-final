<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SliderController extends Controller
{
    public function index()
    {
        // $organ = Organ::find(Auth::user()->organ_id);
        // $sliders = $organ->sliders()->latest()->paginate(10);
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('manager')) {
            $sliders = Slider::where('organ_id', auth()->user()->organ_id)->latest()->paginate(10);
        } else {
            $sliders = Slider::whereNull('organ_id')->latest()->paginate(10);
        }
        return view('dashboard.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('dashboard.sliders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'url'],
            'status' => ['required', 'boolean'],
            'type' => ['required'],
            'image' => ['required', 'image', 'max:2048'],
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/sliders';
            $file->move($destination_path, $file_name);
            $data['image'] = $destination_path . '/' . $file_name;
        }
        $data['organ_id'] = auth()->user()->organ_id ?? null;

        Slider::create($data);

        return redirect()->route('sliders.index')->with('success', 'اسلایدر با موفقیت ایجاد شد.');
    }

    public function edit(Slider $slider)
    {
        return view('dashboard.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'url'],
            'status' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/sliders';
            $file->move($destination_path, $file_name);
            $data['image'] = $destination_path . '/' . $file_name;
        }

        $slider->update($data);

        return redirect()->route('sliders.index')->with('success', 'اسلایدر بروزرسانی شد.');
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();
        return redirect()->route('sliders.index')->with('success', 'اسلایدر حذف شد.');
    }
}
