<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationalVideo;

class EducationalVideoController extends Controller
{
    public function index()
    {
        $videos = EducationalVideo::latest()->paginate(10);
        return view('dashboard.educational-videos.index', compact('videos'));
    }

    public function create()
    {
        return view('dashboard.educational-videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'required|image|max:2048',
            'video_path' => 'required|mimetypes:video/mp4,video/quicktime|max:51200',
            'teacher_name' => 'required|string|max:255',
            'duration_hours' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/courses';
            $file->move($destination_path, $file_name);
            $thumbnail = $destination_path . '/' . $file_name;
        }
        if ($request->hasFile('video_path')) {
            $file = $request->file('video_path');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/courses';
            $file->move($destination_path, $file_name);
            $video = $destination_path . '/' . $file_name;
        }
        // $thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        // $video = $request->file('video_path')->store('videos', 'public');

        EducationalVideo::create([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnail,
            'video_path' => $video,
            'teacher_name' => $request->teacher_name,
            'duration_hours' => $request->duration_hours,
            'status' => $request->status ? true : false,
        ]);

        return redirect()->route('educational-videos.index')->with('success', 'ویدیو با موفقیت ایجاد شد.');
    }

    public function edit(EducationalVideo $educationalVideo)
    {
        return view('dashboard.educational-videos.create', compact('educationalVideo'));
    }

    public function update(Request $request, EducationalVideo $educationalVideo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'video_path' => 'nullable|mimetypes:video/mp4,video/quicktime|max:51200',
            'teacher_name' => 'required|string|max:255',
            'duration_hours' => 'required|integer|min:1',
        ]);

        $data = $request->only(['title', 'description', 'teacher_name', 'duration_hours', 'status']);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/courses';
            $file->move($destination_path, $file_name);
            $data['thumbnail'] = $destination_path . '/' . $file_name;
        }
        if ($request->hasFile('video_path')) {
            $file = $request->file('video_path');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/courses';
            $file->move($destination_path, $file_name);
            $data['video_path'] = $destination_path . '/' . $file_name;
        }
        // if ($request->hasFile('thumbnail')) {
        //     $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        // }

        // if ($request->hasFile('video_path')) {
        //     $data['video_path'] = $request->file('video_path')->store('videos', 'public');
        // }

        $educationalVideo->update($data);

        return redirect()->route('educational-videos.index')->with('success', 'ویدیو با موفقیت بروزرسانی شد.');
    }

    public function destroy(EducationalVideo $educationalVideo)
    {
        $educationalVideo->delete();
        return back()->with('success', 'ویدیو حذف شد.');
    }
}
