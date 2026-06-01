<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'service_id' => ['required', 'exists:services,id'],
        ]);

        $user = auth()->user();
        $serviceId = $request->service_id;

        // اگر قبلاً علاقه‌مندی بوده → حذف
        if ($user->favorites()->where('service_id', $serviceId)->exists()) {
            $user->favorites()->detach($serviceId);

            return response()->json([
                'status' => 'removed',
                'message' => 'از علاقه‌مندی‌ها حذف شد',
            ]);
        }

        // در غیر این صورت → اضافه
        $user->favorites()->attach($serviceId);

        return response()->json([
            'status' => 'added',
            'message' => 'به علاقه‌مندی‌ها اضافه شد',
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        //
    }
}
